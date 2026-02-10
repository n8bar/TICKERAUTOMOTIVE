<?php

declare(strict_types=1);

require_once __DIR__ . '/site-settings.php';

function site_form_get_settings(): array
{
    $settings = $GLOBALS['settings'] ?? null;

    return is_array($settings) ? $settings : [];
}

function site_form_get_contact_form(string $formKey): array
{
    $settings = site_form_get_settings();
    $forms = $settings['contact_forms'] ?? [];

    if (is_array($forms) && isset($forms[$formKey]) && is_array($forms[$formKey])) {
        return $forms[$formKey];
    }

    return [];
}

function site_form_get_delivery_override(): array
{
    $settings = site_form_get_settings();
    $forms = $settings['contact_forms'] ?? [];
    $override = $forms['delivery_override'] ?? [];

    return is_array($override) ? $override : [];
}

function site_form_get_smtp_settings(): array
{
    $settings = site_form_get_settings();
    $smtp = $settings['smtp'] ?? [];

    $defaults = [
        'enabled' => false,
        'host' => '',
        'port' => 587,
        'encryption' => 'tls',
        'username' => '',
        'password' => '',
        'from_email' => '',
        'from_name' => 'Ticker Automotive',
        'reply_to' => '',
        'timeout' => 10,
    ];

    if (!is_array($smtp)) {
        return $defaults;
    }

    return array_merge($defaults, $smtp);
}

function site_form_merge_fields(array $defaults, array $overrides): array
{
    $merged = $defaults;
    foreach ($defaults as $key => $state) {
        $override = $overrides[$key] ?? null;
        if (!is_array($override)) {
            continue;
        }
        $merged[$key]['enabled'] = !empty($override['enabled']);
        $merged[$key]['required'] = !empty($override['required']);
    }

    return $merged;
}

function site_form_sanitize_text(?string $value): string
{
    $value = trim((string) ($value ?? ''));
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);

    return $value ?? '';
}

function site_form_normalize_phone(string $value): string
{
    $digits = preg_replace('/\D+/', '', $value);
    $digits = $digits ?? '';
    $digits = trim($digits);

    if ($digits === '') {
        return trim($value);
    }

    if (strlen($digits) === 11 && str_starts_with($digits, '1')) {
        $digits = substr($digits, 1);
    }

    if (strlen($digits) === 10) {
        return sprintf('(%s) %s-%s', substr($digits, 0, 3), substr($digits, 3, 3), substr($digits, 6));
    }

    return $digits;
}

function site_form_is_spam(array $data): bool
{
    $honeypot = site_form_sanitize_text($data['website'] ?? '');
    if ($honeypot !== '') {
        return true;
    }

    $started = (int) ($data['form_started'] ?? 0);
    if ($started > 0 && (time() - $started) < 3) {
        return true;
    }

    return false;
}

function site_form_collect_values(array $fields, array $source): array
{
    $values = [];
    foreach ($fields as $key => $state) {
        if (empty($state['enabled'])) {
            continue;
        }
        $values[$key] = site_form_sanitize_text($source[$key] ?? '');
    }

    return $values;
}

function site_form_validate(array $fields, array &$values): array
{
    $errors = [];

    foreach ($fields as $key => $state) {
        if (empty($state['enabled'])) {
            continue;
        }
        $value = $values[$key] ?? '';
        if (!empty($state['required']) && $value === '') {
            $errors[] = 'Please fill out the ' . str_replace('_', ' ', $key) . ' field.';
            continue;
        }
        if ($key === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if ($key === 'phone' && $value !== '') {
            $values[$key] = site_form_normalize_phone($value);
        }
    }

    return $errors;
}

function site_form_header_safe(string $value): string
{
    return trim(str_replace(["\r", "\n"], '', $value));
}

function site_form_build_email_body(string $formLabel, array $values, array $meta): string
{
    $lines = [];
    $lines[] = $formLabel;
    $lines[] = str_repeat('=', strlen($formLabel));
    $lines[] = '';
    foreach ($values as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        $lines[] = $label . ': ' . ($value === '' ? '-' : $value);
    }
    $lines[] = '';
    $lines[] = 'Submitted: ' . ($meta['timestamp'] ?? '');
    $lines[] = 'Page: ' . ($meta['page'] ?? '');
    $lines[] = 'IP Address: ' . ($meta['ip'] ?? '');
    $lines[] = 'User Agent: ' . ($meta['user_agent'] ?? '');

    return implode("\n", $lines);
}

function site_form_normalize_email_list($input): array
{
    $emails = [];

    if (is_array($input)) {
        foreach ($input as $value) {
            $value = site_form_sanitize_text((string) $value);
            if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $value;
            }
        }
    } else {
        $value = site_form_sanitize_text((string) $input);
        if ($value !== '') {
            $parts = preg_split('/[\s,;]+/', $value);
            foreach ($parts as $part) {
                $email = site_form_sanitize_text((string) $part);
                if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $email;
                }
            }
        }
    }

    return array_values(array_unique($emails));
}

function site_form_handle_submission(string $formKey, string $formLabel, array $fields, array $formConfig): array
{
    $state = [
        'success' => false,
        'errors' => [],
        'values' => [],
        'message' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($formKey !== (string) ($_POST['form_key'] ?? ''))) {
        return $state;
    }

    $state['values'] = site_form_collect_values($fields, $_POST);

    if (empty($formConfig['enabled'])) {
        $state['errors'][] = 'This form is currently unavailable. Please call us instead.';
        return $state;
    }

    if (site_form_is_spam($_POST)) {
        $state['errors'][] = 'Unable to send your request right now. Please try again.';
        return $state;
    }

    $errors = site_form_validate($fields, $state['values']);
    if (!empty($errors)) {
        $state['errors'] = $errors;
        return $state;
    }

    $settings = site_form_get_settings();
    $smtp = site_form_get_smtp_settings();
    $siteEmail = (string) (($settings['site']['primary_email'] ?? '') ?? '');
    $fromEmail = site_form_sanitize_text((string) ($smtp['from_email'] ?? ''));
    if ($fromEmail === '') {
        $fromEmail = $siteEmail;
    }
    $fromName = site_form_sanitize_text((string) ($smtp['from_name'] ?? 'Ticker Automotive'));

    if ($fromEmail === '') {
        $state['errors'][] = 'Email delivery is not configured yet. Please call us.';
        return $state;
    }

    $recipients = site_form_normalize_email_list($formConfig['recipients'] ?? []);
    $override = site_form_get_delivery_override();
    $overrideEmail = site_form_sanitize_text((string) ($override['email'] ?? ''));
    if (!empty($override['enabled']) && $overrideEmail !== '' && filter_var($overrideEmail, FILTER_VALIDATE_EMAIL)) {
        $recipients = [$overrideEmail];
    }

    if (empty($recipients)) {
        $state['errors'][] = 'Email delivery is not configured yet. Please call us.';
        return $state;
    }

    $userEmail = site_form_sanitize_text((string) ($state['values']['email'] ?? ''));
    $replyTo = site_form_sanitize_text((string) ($smtp['reply_to'] ?? ''));
    if ($replyTo === '' && $userEmail !== '' && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $replyTo = $userEmail;
    }

    $meta = [
        'timestamp' => date('Y-m-d H:i:s T'),
        'page' => $_SERVER['REQUEST_URI'] ?? $formKey,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    ];

    $subject = site_form_header_safe('New ' . $formLabel . ' - Ticker Automotive');
    $body = site_form_build_email_body($formLabel, $state['values'], $meta);

    $sent = site_form_send_smtp($smtp, $recipients, $subject, $body, $fromEmail, $fromName, $replyTo);
    if (!$sent) {
        $state['errors'][] = 'Unable to send your request right now. Please call us.';
        return $state;
    }

    $autoReply = $formConfig['auto_reply'] ?? [];
    if (!empty($autoReply['enabled']) && $userEmail !== '' && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $replySubject = site_form_header_safe(site_form_sanitize_text((string) ($autoReply['subject'] ?? '')));
        $replyBody = site_form_sanitize_text((string) ($autoReply['body'] ?? ''));
        if ($replySubject !== '' && $replyBody !== '') {
            site_form_send_smtp($smtp, [$userEmail], $replySubject, $replyBody, $fromEmail, $fromName, $fromEmail);
        }
    }

    $state['success'] = true;
    $state['message'] = site_form_sanitize_text((string) ($formConfig['thank_you_message'] ?? 'Thanks! We will be in touch soon.'));
    $state['values'] = [];

    return $state;
}

function site_form_send_smtp(array $smtp, array $recipients, string $subject, string $body, string $fromEmail, string $fromName, string $replyTo = ''): bool
{
    if (empty($smtp['host']) || empty($smtp['port'])) {
        return false;
    }

    if (array_key_exists('enabled', $smtp) && !$smtp['enabled']) {
        return false;
    }

    $recipients = array_values(array_filter($recipients));
    if (empty($recipients)) {
        return false;
    }

    $host = (string) $smtp['host'];
    $port = (int) $smtp['port'];
    $timeout = (int) ($smtp['timeout'] ?? 10);
    $encryption = strtolower((string) ($smtp['encryption'] ?? 'tls'));

    $remote = $encryption === 'ssl' ? 'ssl://' . $host : $host;
    $socket = fsockopen($remote, $port, $errno, $errstr, $timeout);
    if (!$socket) {
        return false;
    }

    stream_set_timeout($socket, $timeout);

    $expect = static function ($socket, array $codes): bool {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') {
                break;
            }
        }
        $code = (int) substr($response, 0, 3);
        return in_array($code, $codes, true);
    };

    $command = static function ($socket, string $command, array $codes) use ($expect): bool {
        fwrite($socket, $command . "\r\n");
        return $expect($socket, $codes);
    };

    if (!$expect($socket, [220])) {
        fclose($socket);
        return false;
    }

    $hostname = gethostname() ?: 'localhost';
    if (!$command($socket, 'EHLO ' . $hostname, [250])) {
        fclose($socket);
        return false;
    }

    if ($encryption === 'tls') {
        if (!$command($socket, 'STARTTLS', [220])) {
            fclose($socket);
            return false;
        }
        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($socket);
            return false;
        }
        if (!$command($socket, 'EHLO ' . $hostname, [250])) {
            fclose($socket);
            return false;
        }
    }

    $username = (string) ($smtp['username'] ?? '');
    $password = (string) ($smtp['password'] ?? '');
    if ($username !== '') {
        if (!$command($socket, 'AUTH LOGIN', [334])) {
            fclose($socket);
            return false;
        }
        if (!$command($socket, base64_encode($username), [334])) {
            fclose($socket);
            return false;
        }
        if (!$command($socket, base64_encode($password), [235])) {
            fclose($socket);
            return false;
        }
    }

    $fromEmail = site_form_header_safe($fromEmail);
    $fromName = site_form_header_safe($fromName);
    $replyTo = site_form_header_safe($replyTo);
    $subject = site_form_header_safe($subject);

    if (!$command($socket, 'MAIL FROM:<' . $fromEmail . '>', [250])) {
        fclose($socket);
        return false;
    }

    foreach ($recipients as $recipient) {
        if (!$command($socket, 'RCPT TO:<' . $recipient . '>', [250, 251])) {
            fclose($socket);
            return false;
        }
    }

    if (!$command($socket, 'DATA', [354])) {
        fclose($socket);
        return false;
    }

    $headers = [
        'From: ' . $fromName . ' <' . $fromEmail . '>',
        'Reply-To: ' . ($replyTo !== '' ? $replyTo : $fromEmail),
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
    ];

    $message = implode("\r\n", $headers) . "\r\n\r\n" . $body;
    $message = str_replace("\r\n.", "\r\n..", str_replace("\n", "\r\n", $message));

    fwrite($socket, $message . "\r\n.\r\n");
    if (!$expect($socket, [250])) {
        fclose($socket);
        return false;
    }

    $command($socket, 'QUIT', [221]);
    fclose($socket);

    return true;
}
