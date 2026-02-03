<?php

declare(strict_types=1);

function owner_get_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $configPath = __DIR__ . '/../config.php';
    if (!is_file($configPath)) {
        owner_abort_setup('Owner portal is not configured. Copy owner/config.sample.php to owner/config.php and update values.');
    }

    require $configPath;

    if (!isset($OWNER_CONFIG) || !is_array($OWNER_CONFIG)) {
        owner_abort_setup('Owner portal config is missing or invalid.');
    }

    $config = $OWNER_CONFIG;

    return $config;
}

function owner_abort_setup(string $message): void
{
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=UTF-8');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }

    echo $message;
    exit;
}

function owner_is_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        return true;
    }

    return false;
}

function owner_send_no_cache_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}

function owner_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $config = owner_get_config();
    $baseUrl = $config['app']['base_url'] ?? '/owner';
    $sessionName = $config['app']['session_name'] ?? 'owner_session';

    $cookieParams = [
        'lifetime' => 0,
        'path' => $baseUrl,
        'domain' => '',
        'secure' => owner_is_https(),
        'httponly' => true,
        'samesite' => 'Strict',
    ];

    session_name($sessionName);
    session_set_cookie_params($cookieParams);
    session_start();
}

function owner_csrf_token(): string
{
    owner_start_session();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function owner_verify_csrf(?string $token): bool
{
    owner_start_session();

    if (empty($_SESSION['csrf_token']) || !$token) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function owner_set_flash(string $type, string $message): void
{
    owner_start_session();
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function owner_get_flash(): ?array
{
    owner_start_session();

    if (empty($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function owner_find_account(string $email): ?array
{
    $config = owner_get_config();
    $accounts = $config['accounts'] ?? [];

    foreach ($accounts as $account) {
        if (empty($account['enabled'])) {
            continue;
        }
        if (empty($account['email'])) {
            continue;
        }

        if (strcasecmp($account['email'], $email) === 0) {
            return $account;
        }
    }

    return null;
}

function owner_verify_credentials(string $email, string $password): ?array
{
    $account = owner_find_account($email);

    if (!$account || empty($account['password_hash'])) {
        return null;
    }

    if (!password_verify($password, $account['password_hash'])) {
        return null;
    }

    return $account;
}

function owner_is_locked_out(): bool
{
    owner_start_session();

    if (empty($_SESSION['lockout_until'])) {
        return false;
    }

    return time() < (int) $_SESSION['lockout_until'];
}

function owner_note_failed_login(): void
{
    owner_start_session();

    $config = owner_get_config();
    $security = $config['security'] ?? [];
    $maxAttempts = (int) ($security['max_login_attempts'] ?? 5);
    $lockoutMinutes = (int) ($security['lockout_minutes'] ?? 10);

    $attempts = (int) ($_SESSION['failed_logins'] ?? 0);
    $attempts++;
    $_SESSION['failed_logins'] = $attempts;

    if ($attempts >= $maxAttempts) {
        $_SESSION['failed_logins'] = 0;
        $_SESSION['lockout_until'] = time() + ($lockoutMinutes * 60);
    }
}

function owner_clear_failed_logins(): void
{
    owner_start_session();
    unset($_SESSION['failed_logins'], $_SESSION['lockout_until']);
}

function owner_requires_otp(): bool
{
    $config = owner_get_config();

    return !empty($config['otp']['enabled']);
}

function owner_send_otp(array $account): bool
{
    $config = owner_get_config();
    $otpConfig = $config['otp'] ?? [];
    $ttlMinutes = (int) ($otpConfig['ttl_minutes'] ?? 10);

    $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    owner_start_session();
    $_SESSION['otp'] = [
        'hash' => hash('sha256', $code),
        'expires_at' => time() + ($ttlMinutes * 60),
        'email' => $account['email'] ?? '',
        'attempts' => 0,
    ];

    $subject = $otpConfig['subject'] ?? 'Your login code';
    $fromEmail = $otpConfig['from_email'] ?? '';
    $fromName = $otpConfig['from_name'] ?? 'Ticker Automotive';

    $messageLines = [
        'Your Ticker Automotive login code is:',
        $code,
        '',
        'This code expires in ' . $ttlMinutes . ' minutes.',
    ];

    $message = implode("\n", $messageLines);

    return owner_send_mail($account['email'] ?? '', $subject, $message, $fromEmail, $fromName);
}

function owner_verify_otp(string $code): bool
{
    owner_start_session();

    if (empty($_SESSION['otp']) || empty($_SESSION['otp']['hash'])) {
        return false;
    }

    $otp = $_SESSION['otp'];
    $expiresAt = (int) ($otp['expires_at'] ?? 0);

    if (time() > $expiresAt) {
        unset($_SESSION['otp']);
        return false;
    }

    $config = owner_get_config();
    $security = $config['security'] ?? [];
    $maxAttempts = (int) ($security['otp_max_attempts'] ?? 5);

    $attempts = (int) ($otp['attempts'] ?? 0);
    $attempts++;
    $_SESSION['otp']['attempts'] = $attempts;

    if ($attempts > $maxAttempts) {
        unset($_SESSION['otp']);
        return false;
    }

    $hash = hash('sha256', $code);
    if (!hash_equals($otp['hash'], $hash)) {
        return false;
    }

    unset($_SESSION['otp']);
    return true;
}

function owner_send_mail(string $to, string $subject, string $message, string $fromEmail, string $fromName): bool
{
    if ($to === '' || $fromEmail === '') {
        return false;
    }

    $headers = [
        'From: ' . $fromName . ' <' . $fromEmail . '>',
        'Reply-To: ' . $fromEmail,
        'X-Mailer: PHP/' . phpversion(),
        'Content-Type: text/plain; charset=UTF-8',
    ];

    return mail($to, $subject, $message, implode("\r\n", $headers));
}

function owner_login_user(array $account): void
{
    owner_start_session();
    session_regenerate_id(true);

    $_SESSION['auth'] = [
        'email' => $account['email'] ?? '',
        'name' => $account['name'] ?? 'Admin',
        'role' => $account['role'] ?? 'admin',
        'hidden' => !empty($account['hidden']),
    ];

    $_SESSION['last_active'] = time();
    unset($_SESSION['pending_account']);
    owner_clear_failed_logins();
}

function owner_pending_account(): ?array
{
    owner_start_session();

    if (empty($_SESSION['pending_account'])) {
        return null;
    }

    return $_SESSION['pending_account'];
}

function owner_set_pending_account(array $account): void
{
    owner_start_session();

    $_SESSION['pending_account'] = [
        'email' => $account['email'] ?? '',
        'name' => $account['name'] ?? 'Admin',
        'role' => $account['role'] ?? 'admin',
        'hidden' => !empty($account['hidden']),
    ];
}

function owner_clear_pending_account(): void
{
    owner_start_session();
    unset($_SESSION['pending_account'], $_SESSION['otp']);
}

function owner_require_login(): void
{
    owner_start_session();

    if (empty($_SESSION['auth'])) {
        owner_redirect('/owner/login/');
    }

    $config = owner_get_config();
    $timeoutMinutes = (int) ($config['app']['session_timeout_minutes'] ?? 60);
    $lastActive = (int) ($_SESSION['last_active'] ?? 0);

    if ($lastActive && (time() - $lastActive) > ($timeoutMinutes * 60)) {
        owner_logout();
        owner_set_flash('error', 'Session expired. Please sign in again.');
        owner_redirect('/owner/login/');
    }

    $_SESSION['last_active'] = time();
}

function owner_logout(): void
{
    owner_start_session();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function owner_redirect(string $path): void
{
    if (!headers_sent()) {
        header('Location: ' . $path);
    }
    exit;
}
