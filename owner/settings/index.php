<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/settings.php';

owner_send_no_cache_headers();
owner_require_login();

$settings = owner_load_settings();
$errors = [];
$notices = [];
$flash = owner_get_flash();

$formLabels = [
    'appointments' => 'Schedule an Appointment',
    'contact_us' => 'Contact Us',
];

$fieldLabels = [
    'name' => 'Full name',
    'phone' => 'Phone number',
    'email' => 'Email address',
    'vehicle' => 'Vehicle',
    'preferred_time' => 'Preferred time',
    'message' => 'Message',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!owner_verify_csrf(is_string($csrfToken) ? $csrfToken : null)) {
        $errors[] = 'Security check failed. Please try again.';
    } else {
        $input = $_POST['settings'] ?? [];
        if (!is_array($input)) {
            $input = [];
        }

        $settings = owner_build_settings_from_post($input, $settings);

        if (owner_save_settings($settings)) {
            owner_set_flash('success', 'Settings saved.');
            owner_redirect('/owner/settings/');
        } else {
            $errors[] = 'Unable to save settings. Check file permissions.';
        }
    }
}

$user = $_SESSION['auth'] ?? [];
$csrfToken = owner_csrf_token();

function owner_checked(bool $value): string
{
    return $value ? ' checked' : '';
}

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="robots" content="noindex, nofollow">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;display=swap">
        <link rel="stylesheet" href="/site-shell.css">
        <link rel="stylesheet" href="/owner/owner.css">
        <title>Owner Settings - Ticker Automotive</title>
    </head>
    <body class="owner-page">
        <div class="owner-shell">
            <header class="owner-header">
                <div class="container owner-header-inner">
                    <div class="owner-brand">
                        <span class="owner-brand-title">Ticker Automotive</span>
                        <span class="owner-brand-subtitle">Owner Portal</span>
                    </div>
                    <div class="owner-user">
                        <span class="owner-user-name">Signed in as <?php echo htmlspecialchars($user['name'] ?? 'Admin', ENT_QUOTES); ?></span>
                        <a class="owner-link" href="/owner/logout.php">Sign out</a>
                    </div>
                </div>
            </header>
            <main class="owner-main">
                <section class="owner-card owner-card-wide">
                    <h1 class="owner-title">Settings</h1>
                    <p class="owner-subtitle">Update contact details and form behavior used across the site.</p>

                    <?php if ($flash): ?>
                        <div class="owner-alert owner-alert-<?php echo htmlspecialchars($flash['type'], ENT_QUOTES); ?>">
                            <?php echo htmlspecialchars($flash['message'], ENT_QUOTES); ?>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($notices as $notice): ?>
                        <div class="owner-alert owner-alert-success">
                            <?php echo htmlspecialchars($notice, ENT_QUOTES); ?>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($errors as $error): ?>
                        <div class="owner-alert owner-alert-error">
                            <?php echo htmlspecialchars($error, ENT_QUOTES); ?>
                        </div>
                    <?php endforeach; ?>

                    <form class="owner-form" method="post" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">

                        <div class="owner-section">
                            <h2 class="owner-section-title">Site Contact Info</h2>
                            <div class="owner-grid">
                                <label class="owner-field">
                                    <span class="owner-label">Primary phone</span>
                                    <input class="owner-input" type="text" name="settings[site][primary_phone]" value="<?php echo htmlspecialchars($settings['site']['primary_phone'] ?? '', ENT_QUOTES); ?>">
                                </label>
                                <label class="owner-field">
                                    <span class="owner-label">After hours phone</span>
                                    <input class="owner-input" type="text" name="settings[site][after_hours_phone]" value="<?php echo htmlspecialchars($settings['site']['after_hours_phone'] ?? '', ENT_QUOTES); ?>">
                                </label>
                                <label class="owner-field">
                                    <span class="owner-label">Primary email</span>
                                    <input class="owner-input" type="email" name="settings[site][primary_email]" value="<?php echo htmlspecialchars($settings['site']['primary_email'] ?? '', ENT_QUOTES); ?>">
                                </label>
                                <label class="owner-field">
                                    <span class="owner-label">Street address</span>
                                    <input class="owner-input" type="text" name="settings[site][address_line1]" value="<?php echo htmlspecialchars($settings['site']['address_line1'] ?? '', ENT_QUOTES); ?>">
                                </label>
                                <label class="owner-field">
                                    <span class="owner-label">City, State ZIP</span>
                                    <input class="owner-input" type="text" name="settings[site][address_line2]" value="<?php echo htmlspecialchars($settings['site']['address_line2'] ?? '', ENT_QUOTES); ?>">
                                </label>
                            </div>
                        </div>

                        <?php foreach ($formLabels as $formKey => $formLabel): ?>
                            <?php
                                $formSettings = $settings['contact_forms'][$formKey] ?? [];
                                $recipients = $formSettings['recipients'] ?? [];
                                $recipientValue = implode(', ', $recipients);
                                $fields = $formSettings['fields'] ?? [];
                                $autoReply = $formSettings['auto_reply'] ?? [];
                            ?>
                            <div class="owner-section">
                                <div class="owner-section-header">
                                    <h2 class="owner-section-title"><?php echo htmlspecialchars($formLabel, ENT_QUOTES); ?> Form</h2>
                                    <label class="owner-toggle">
                                        <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][enabled]" value="1"<?php echo owner_checked(!empty($formSettings['enabled'])); ?>>
                                        <span>Enabled</span>
                                    </label>
                                </div>
                                <div class="owner-grid">
                                    <label class="owner-field owner-field-full">
                                        <span class="owner-label">Recipient email(s)</span>
                                        <input class="owner-input" type="text" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][recipients]" value="<?php echo htmlspecialchars($recipientValue, ENT_QUOTES); ?>" placeholder="email1@domain.com, email2@domain.com">
                                        <span class="owner-help">Separate multiple emails with commas.</span>
                                    </label>
                                </div>
                                <div class="owner-fields-grid">
                                    <?php foreach ($fieldLabels as $fieldKey => $fieldLabel): ?>
                                        <?php $fieldState = $fields[$fieldKey] ?? ['enabled' => true, 'required' => false]; ?>
                                        <div class="owner-field-row">
                                            <div class="owner-field-meta">
                                                <span class="owner-field-name"><?php echo htmlspecialchars($fieldLabel, ENT_QUOTES); ?></span>
                                            </div>
                                            <label class="owner-toggle">
                                                <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][fields][<?php echo htmlspecialchars($fieldKey, ENT_QUOTES); ?>][enabled]" value="1"<?php echo owner_checked(!empty($fieldState['enabled'])); ?>>
                                                <span>Show</span>
                                            </label>
                                            <label class="owner-toggle">
                                                <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][fields][<?php echo htmlspecialchars($fieldKey, ENT_QUOTES); ?>][required]" value="1"<?php echo owner_checked(!empty($fieldState['required'])); ?>>
                                                <span>Required</span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <label class="owner-field owner-field-full">
                                    <span class="owner-label">Thank you message</span>
                                    <textarea class="owner-input owner-textarea" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][thank_you_message]" rows="3"><?php echo htmlspecialchars($formSettings['thank_you_message'] ?? '', ENT_QUOTES); ?></textarea>
                                </label>
                                <div class="owner-section-sub">
                                    <h3 class="owner-section-subtitle">Auto-reply</h3>
                                    <label class="owner-toggle">
                                        <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][auto_reply][enabled]" value="1"<?php echo owner_checked(!empty($autoReply['enabled'])); ?>>
                                        <span>Send auto-reply</span>
                                    </label>
                                    <div class="owner-grid">
                                        <label class="owner-field owner-field-full">
                                            <span class="owner-label">Auto-reply subject</span>
                                            <input class="owner-input" type="text" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][auto_reply][subject]" value="<?php echo htmlspecialchars($autoReply['subject'] ?? '', ENT_QUOTES); ?>">
                                        </label>
                                        <label class="owner-field owner-field-full">
                                            <span class="owner-label">Auto-reply message</span>
                                            <textarea class="owner-input owner-textarea" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][auto_reply][body]" rows="4"><?php echo htmlspecialchars($autoReply['body'] ?? '', ENT_QUOTES); ?></textarea>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="owner-actions">
                            <button class="btn btn-primary owner-button" type="submit">Save Settings</button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </body>
</html>
