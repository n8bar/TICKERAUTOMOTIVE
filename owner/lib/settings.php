<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function owner_default_settings(): array
{
    return [
        'site' => [
            'primary_phone' => '(435) 656-9560',
            'after_hours_phone' => '(435) 467-5971',
            'primary_email' => 'service84784@gmail.com',
            'business_hours' => '',
            'address_line1' => '680 North State Street',
            'address_line2' => 'Hildale, UT 84784',
        ],
        'smtp' => [
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
        ],
        'contact_forms' => [
            'appointments' => [
                'enabled' => true,
                'recipients' => ['service84784@gmail.com'],
                'thank_you_message' => 'Thanks for reaching out! We will contact you shortly to confirm your appointment.',
                'auto_reply' => [
                    'enabled' => false,
                    'subject' => 'We received your appointment request',
                    'body' => "Thanks for contacting Ticker Automotive. We'll follow up soon to confirm your appointment.",
                ],
                'fields' => (function (): array {
                    $fields = owner_default_form_fields();
                    foreach (['service', 'year', 'make', 'model', 'license_plate', 'vin'] as $fieldKey) {
                        if (isset($fields[$fieldKey])) {
                            $fields[$fieldKey]['enabled'] = true;
                        }
                    }
                    if (isset($fields['vehicle'])) {
                        $fields['vehicle']['enabled'] = false;
                    }
                    return $fields;
                })(),
            ],
            'contact_us' => [
                'enabled' => true,
                'recipients' => ['service84784@gmail.com'],
                'thank_you_message' => 'Thanks for the message! We will get back to you soon.',
                'auto_reply' => [
                    'enabled' => false,
                    'subject' => 'We received your message',
                    'body' => "Thanks for contacting Ticker Automotive. We'll be in touch shortly.",
                ],
                'fields' => owner_default_form_fields(),
            ],
            'delivery_override' => [
                'enabled' => false,
                'email' => '',
            ],
        ],
    ];
}

function owner_default_form_fields(): array
{
    return [
        'name' => ['enabled' => true, 'required' => true],
        'phone' => ['enabled' => true, 'required' => true],
        'email' => ['enabled' => true, 'required' => false],
        'service' => ['enabled' => false, 'required' => false],
        'year' => ['enabled' => false, 'required' => false],
        'make' => ['enabled' => false, 'required' => false],
        'model' => ['enabled' => false, 'required' => false],
        'license_plate' => ['enabled' => false, 'required' => false],
        'vin' => ['enabled' => false, 'required' => false],
        'vehicle' => ['enabled' => true, 'required' => false],
        'preferred_time' => ['enabled' => true, 'required' => false],
        'message' => ['enabled' => true, 'required' => false],
    ];
}

function owner_merge_settings(array $base, array $override): array
{
    foreach ($override as $key => $value) {
        if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
            $base[$key] = owner_merge_settings($base[$key], $value);
        } else {
            $base[$key] = $value;
        }
    }

    return $base;
}

function owner_load_settings(): array
{
    $config = owner_get_config();
    $defaults = owner_default_settings();

    $defaultFile = $config['settings']['default_file'] ?? '';
    if ($defaultFile && is_file($defaultFile)) {
        $contents = file_get_contents($defaultFile);
        $decoded = json_decode((string) $contents, true);
        if (is_array($decoded)) {
            $defaults = owner_merge_settings($defaults, $decoded);
        }
    }

    $dataFile = $config['settings']['data_file'] ?? '';
    if ($dataFile && is_file($dataFile)) {
        $contents = file_get_contents($dataFile);
        $decoded = json_decode((string) $contents, true);
        if (is_array($decoded)) {
            return owner_merge_settings($defaults, $decoded);
        }
    }

    return $defaults;
}

function owner_save_settings(array $settings): bool
{
    $config = owner_get_config();
    $dataFile = $config['settings']['data_file'] ?? '';

    if ($dataFile === '') {
        return false;
    }

    $dataDir = dirname($dataFile);
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }

    $payload = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    return file_put_contents($dataFile, (string) $payload, LOCK_EX) !== false;
}

function owner_sanitize_text(?string $value): string
{
    $value = $value ?? '';
    $value = trim($value);
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);

    return $value;
}

function owner_sanitize_email_list(?string $value): array
{
    $value = owner_sanitize_text($value);
    if ($value === '') {
        return [];
    }

    $parts = preg_split('/[\s,;]+/', $value);
    $emails = [];

    foreach ($parts as $part) {
        $email = trim($part);
        if ($email === '') {
            continue;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            continue;
        }
        $emails[] = $email;
    }

    return array_values(array_unique($emails));
}

function owner_build_settings_from_post(array $input, array $current): array
{
    $settings = $current;

    $siteInput = $input['site'] ?? [];

    if (array_key_exists('primary_phone', $siteInput)) {
        $settings['site']['primary_phone'] = owner_sanitize_text($siteInput['primary_phone'] ?? '');
    }
    if (array_key_exists('after_hours_phone', $siteInput)) {
        $settings['site']['after_hours_phone'] = owner_sanitize_text($siteInput['after_hours_phone'] ?? '');
    }
    if (array_key_exists('primary_email', $siteInput)) {
        $settings['site']['primary_email'] = owner_sanitize_text($siteInput['primary_email'] ?? '');
    }
    if (array_key_exists('business_hours', $siteInput)) {
        $settings['site']['business_hours'] = owner_sanitize_text($siteInput['business_hours'] ?? '');
    }
    if (array_key_exists('address_line1', $siteInput)) {
        $settings['site']['address_line1'] = owner_sanitize_text($siteInput['address_line1'] ?? '');
    }
    if (array_key_exists('address_line2', $siteInput)) {
        $settings['site']['address_line2'] = owner_sanitize_text($siteInput['address_line2'] ?? '');
    }

    $forms = ['appointments', 'contact_us'];
    $overrideInput = $input['contact_forms']['delivery_override'] ?? null;
    if (is_array($overrideInput)) {
        $settings['contact_forms']['delivery_override']['enabled'] = !empty($overrideInput['enabled']);
        $overrideEmail = owner_normalize_email((string) ($overrideInput['email'] ?? ''));
        if ($overrideEmail !== '' && !filter_var($overrideEmail, FILTER_VALIDATE_EMAIL)) {
            $overrideEmail = '';
        }
        $settings['contact_forms']['delivery_override']['email'] = $overrideEmail;
    }
    foreach ($forms as $formKey) {
        $formInput = $input['contact_forms'][$formKey] ?? [];
        $settings['contact_forms'][$formKey]['enabled'] = !empty($formInput['enabled']);
        $settings['contact_forms'][$formKey]['recipients'] = owner_sanitize_email_list($formInput['recipients'] ?? '');
        $settings['contact_forms'][$formKey]['thank_you_message'] = owner_sanitize_text($formInput['thank_you_message'] ?? '');

        $settings['contact_forms'][$formKey]['auto_reply']['enabled'] = !empty($formInput['auto_reply']['enabled']);
        $settings['contact_forms'][$formKey]['auto_reply']['subject'] = owner_sanitize_text($formInput['auto_reply']['subject'] ?? '');
        $settings['contact_forms'][$formKey]['auto_reply']['body'] = owner_sanitize_text($formInput['auto_reply']['body'] ?? '');

        $fields = owner_default_form_fields();
        foreach ($fields as $fieldKey => $defaultField) {
            $fieldInput = $formInput['fields'][$fieldKey] ?? [];
            $enabled = !empty($fieldInput['enabled']);
            $currentField = $settings['contact_forms'][$formKey]['fields'][$fieldKey] ?? $defaultField;
            $required = !empty($currentField['required']);
            if ($enabled) {
                $required = !empty($fieldInput['required']);
            }

            $settings['contact_forms'][$formKey]['fields'][$fieldKey] = [
                'enabled' => $enabled,
                'required' => $required,
            ];
        }
    }

    return $settings;
}
