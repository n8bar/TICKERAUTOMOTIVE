<?php

declare(strict_types=1);

/*
 * Copy this file to owner/config.php and update the values.
 *
 * Generate password hashes with:
 * php -r "echo password_hash('YOUR_PASSWORD_HERE', PASSWORD_DEFAULT);"
 */

$OWNER_CONFIG = [
    'app' => [
        'name' => 'Ticker Automotive Owner',
        'base_url' => '/owner',
        'session_name' => 'ticker_owner',
        'session_timeout_minutes' => 60,
    ],
    'security' => [
        'max_login_attempts' => 5,
        'lockout_minutes' => 10,
        'otp_max_attempts' => 5,
    ],
    'accounts' => [
        [
            'email' => 'owner@example.com',
            'name' => 'Owner',
            'role' => 'owner',
            'password_hash' => 'REPLACE_WITH_HASH',
            'enabled' => true,
        ],
        [
            'email' => 'admin2@example.com',
            'name' => 'Admin 2',
            'role' => 'admin',
            'password_hash' => 'REPLACE_WITH_HASH',
            'enabled' => false,
        ],
        [
            'email' => 'admin3@example.com',
            'name' => 'Admin 3',
            'role' => 'admin',
            'password_hash' => 'REPLACE_WITH_HASH',
            'enabled' => false,
        ],
        [
            'email' => 'author@example.com',
            'name' => 'Author',
            'role' => 'author',
            'password_hash' => 'REPLACE_WITH_HASH',
            'enabled' => true,
            'hidden' => true,
        ],
    ],
    'otp' => [
        'enabled' => false,
        'ttl_minutes' => 10,
        'from_email' => 'no-reply@tickerautomotive.com',
        'from_name' => 'Ticker Automotive',
        'subject' => 'Your Ticker Automotive login code',
    ],
    'settings' => [
        'data_file' => __DIR__ . '/data/settings.json',
        'default_file' => __DIR__ . '/data/settings.sample.json',
    ],
];
