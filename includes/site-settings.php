<?php

declare(strict_types=1);

$siteSettingsDefaults = [
    'primary_phone' => '(435) 656-9560',
    'after_hours_phone' => '(435) 467-5971',
    'primary_email' => 'info@tickerautomotive.com',
    'address_line1' => '391 W Utah Ave',
    'address_line2' => 'Hildale, UT 84784',
];

$siteSettings = $siteSettingsDefaults;
$settings = null;
$configPath = __DIR__ . '/../owner/config.php';

if (is_file($configPath)) {
    require_once __DIR__ . '/../owner/lib/settings.php';
    $settings = owner_load_settings();
} else {
    $settings = ['site' => $siteSettingsDefaults];
    $settings = site_settings_merge($settings, site_settings_decode(__DIR__ . '/../owner/data/settings.sample.json'));
    $settings = site_settings_merge($settings, site_settings_decode(__DIR__ . '/../owner/data/settings.json'));
}

if (is_array($settings) && isset($settings['site']) && is_array($settings['site'])) {
    $siteSettings = array_merge($siteSettingsDefaults, $settings['site']);
}

$sitePrimaryPhone = (string) ($siteSettings['primary_phone'] ?? '');
$siteAfterHoursPhone = (string) ($siteSettings['after_hours_phone'] ?? '');
$sitePrimaryEmail = (string) ($siteSettings['primary_email'] ?? '');
$siteAddressLine1 = (string) ($siteSettings['address_line1'] ?? '');
$siteAddressLine2 = (string) ($siteSettings['address_line2'] ?? '');

$siteAddressComma = site_join_nonempty([$siteAddressLine1, $siteAddressLine2], ', ');
$siteAddressInline = site_join_nonempty([$siteAddressLine1, $siteAddressLine2], ' ');
$siteAddressQuery = rawurlencode($siteAddressComma);
$siteAddressHereQuery = rawurlencode(site_join_nonempty([$siteAddressInline, 'United States'], ', '));

$sitePrimaryPhoneHref = site_phone_href($sitePrimaryPhone);
$siteAfterHoursPhoneHref = site_phone_href($siteAfterHoursPhone);
$sitePrimaryEmailHref = site_mailto_href($sitePrimaryEmail);

$siteNapLine = site_join_nonempty(['Ticker Automotive', $siteAddressInline, $sitePrimaryPhone], ' • ');
$siteNapFooterBase = site_join_nonempty(['Ticker Automotive', $siteAddressInline], ', ');
$siteNapFooter = site_join_nonempty([$siteNapFooterBase, $sitePrimaryPhone], ' ');

function site_settings_decode(string $path): array
{
    if (!is_file($path)) {
        return [];
    }

    $contents = file_get_contents($path);
    if ($contents === false) {
        return [];
    }

    $decoded = json_decode((string) $contents, true);

    return is_array($decoded) ? $decoded : [];
}

function site_settings_merge(array $base, array $override): array
{
    foreach ($override as $key => $value) {
        if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
            $base[$key] = site_settings_merge($base[$key], $value);
        } else {
            $base[$key] = $value;
        }
    }

    return $base;
}

function site_phone_href(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone);

    if ($digits === null || $digits === '') {
        return '';
    }

    return 'tel:' . $digits;
}

function site_mailto_href(string $email): string
{
    $email = trim($email);

    if ($email === '') {
        return '';
    }

    return 'mailto:' . $email;
}

function site_join_nonempty(array $parts, string $separator): string
{
    $filtered = [];

    foreach ($parts as $part) {
        $value = trim((string) $part);
        if ($value === '') {
            continue;
        }
        $filtered[] = $value;
    }

    return implode($separator, $filtered);
}
