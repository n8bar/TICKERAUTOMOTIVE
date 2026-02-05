<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/settings.php';

owner_send_no_cache_headers();
owner_require_login();

$settings = owner_load_settings();
$errors = [];
$notices = [];
$flash = owner_get_flash();
$allowedTabs = ['general', 'forms', 'users'];
$requestedTab = $_GET['tab'] ?? '';
if (!in_array($requestedTab, $allowedTabs, true)) {
    $requestedTab = $_SESSION['owner_active_tab'] ?? 'general';
}
if (in_array($_GET['tab'] ?? '', $allowedTabs, true)) {
    $_SESSION['owner_active_tab'] = $requestedTab;
}
$accordionState = $_SESSION['owner_accordion_state'] ?? [];
if (!is_array($accordionState)) {
    $accordionState = [];
}

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
            $postedAccordion = $_POST['accordion_state'] ?? [];
            if (is_array($postedAccordion)) {
                $nextAccordion = [];
                foreach (array_keys($formLabels) as $formKey) {
                    $nextAccordion[$formKey] = !empty($postedAccordion[$formKey]);
                }
                $_SESSION['owner_accordion_state'] = $nextAccordion;
                $accordionState = $nextAccordion;
            }
            $activeTab = $_POST['active_tab'] ?? '';
            if (is_string($activeTab) && in_array($activeTab, $allowedTabs, true)) {
                $_SESSION['owner_active_tab'] = $activeTab;
                owner_set_flash('success', 'Settings saved.');
                owner_redirect('/owner/settings/?tab=' . rawurlencode($activeTab));
            }
            owner_set_flash('success', 'Settings saved.');
            owner_redirect('/owner/settings/');
        } else {
            $errors[] = 'Unable to save settings. Check file permissions.';
        }
    }
}

$user = $_SESSION['auth'] ?? [];
$csrfToken = owner_csrf_token();
$isAuthor = ($user['role'] ?? '') === 'author';
$config = owner_get_config();
$accounts = $config['accounts'] ?? [];
$roleOrder = ['owner', 'admin'];

if ($isAuthor) {
    $roleOrder[] = 'author';
}

$roleLabels = [
    'owner' => 'Owner',
    'admin' => 'Admin',
    'author' => 'Author',
];

$accountsByRole = [];
foreach ($roleOrder as $role) {
    $accountsByRole[$role] = [];
}

foreach ($accounts as $account) {
    if (empty($account['enabled'])) {
        continue;
    }

    $role = $account['role'] ?? 'admin';
    if (!array_key_exists($role, $accountsByRole)) {
        continue;
    }

    if (!$isAuthor && !empty($account['hidden'])) {
        continue;
    }

    $accountsByRole[$role][] = $account;
}

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
                        <input type="hidden" name="active_tab" value="<?php echo htmlspecialchars($requestedTab, ENT_QUOTES); ?>" data-active-tab>

                        <div class="owner-tabs" role="tablist" aria-label="Settings sections" data-default-tab="<?php echo htmlspecialchars($requestedTab, ENT_QUOTES); ?>">
                            <button class="owner-tab is-active" type="button" data-tab="general" role="tab" aria-selected="true" aria-controls="tab-general" id="tab-general-button">General</button>
                            <button class="owner-tab" type="button" data-tab="forms" role="tab" aria-selected="false" aria-controls="tab-forms" id="tab-forms-button">Forms</button>
                            <button class="owner-tab" type="button" data-tab="users" role="tab" aria-selected="false" aria-controls="tab-users" id="tab-users-button">Users</button>
                        </div>

                        <div class="owner-tab-panels">
                            <section class="owner-tab-panel" data-tab-panel="general" role="tabpanel" aria-labelledby="tab-general-button" id="tab-general">
                                <div class="owner-section">
                                    <h2 class="owner-section-title">Site Contact Info</h2>
                                    <div class="owner-grid">
                                        <label class="owner-field">
                                            <span class="owner-label">Primary email</span>
                                            <input class="owner-input" type="email" name="settings[site][primary_email]" value="<?php echo htmlspecialchars($settings['site']['primary_email'] ?? '', ENT_QUOTES); ?>">
                                        </label>
                                        <label class="owner-field">
                                            <span class="owner-label">Business hours</span>
                                            <input class="owner-input" type="text" name="settings[site][business_hours]" value="<?php echo htmlspecialchars($settings['site']['business_hours'] ?? '', ENT_QUOTES); ?>">
                                        </label>
                                    </div>
                                </div>
                            </section>

                            <section class="owner-tab-panel" data-tab-panel="forms" role="tabpanel" aria-labelledby="tab-forms-button" id="tab-forms" hidden>
                                <?php foreach ($formLabels as $formKey => $formLabel): ?>
                                    <?php
                                        $formSettings = $settings['contact_forms'][$formKey] ?? [];
                                        $recipients = $formSettings['recipients'] ?? [];
                                        $recipientValue = implode(', ', $recipients);
                                        $fields = $formSettings['fields'] ?? [];
                                        $autoReply = $formSettings['auto_reply'] ?? [];
                                        $isOpen = !empty($accordionState[$formKey]);
                                    ?>
                                    <div class="owner-accordion" data-accordion="<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>">
                                        <div class="owner-accordion-header">
                                            <button class="owner-accordion-toggle" type="button" aria-expanded="<?php echo $isOpen ? 'true' : 'false'; ?>" aria-controls="form-<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>" id="form-toggle-<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>">
                                                <span class="owner-accordion-title"><?php echo htmlspecialchars($formLabel, ENT_QUOTES); ?> Form</span>
                                                <span class="owner-accordion-indicator"><?php echo $isOpen ? 'Collapse' : 'Expand'; ?></span>
                                            </button>
                                            <input type="hidden" name="accordion_state[<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>]" value="<?php echo $isOpen ? '1' : '0'; ?>" data-accordion-state="<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>">
                                            <label class="owner-toggle owner-toggle-inline">
                                                <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][enabled]" value="1"<?php echo owner_checked(!empty($formSettings['enabled'])); ?>>
                                                <span>Enabled</span>
                                            </label>
                                        </div>
                                        <div class="owner-accordion-panel" id="form-<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>" role="region" aria-labelledby="form-toggle-<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>"<?php echo $isOpen ? '' : ' hidden'; ?>>
                                            <div class="owner-section owner-section-compact">
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
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </section>

                            <section class="owner-tab-panel" data-tab-panel="users" role="tabpanel" aria-labelledby="tab-users-button" id="tab-users" hidden>
                                <div class="owner-section">
                                    <h2 class="owner-section-title">Users</h2>
                                    <p class="owner-subtitle owner-subtitle-tight">Review who has access to the owner portal.</p>
                                    <div class="owner-users-grid">
                                        <?php foreach ($roleOrder as $role): ?>
                                            <div class="owner-user-card">
                                                <h3 class="owner-user-role"><?php echo htmlspecialchars($roleLabels[$role] ?? ucfirst($role), ENT_QUOTES); ?></h3>
                                                <?php if (empty($accountsByRole[$role])): ?>
                                                    <p class="owner-help">No active accounts yet.</p>
                                                <?php else: ?>
                                                    <?php foreach ($accountsByRole[$role] as $account): ?>
                                                        <div class="owner-user-entry">
                                                            <span class="owner-user-entry-name"><?php echo htmlspecialchars($account['name'] ?? 'User', ENT_QUOTES); ?></span>
                                                            <span class="owner-user-entry-email"><?php echo htmlspecialchars($account['email'] ?? '', ENT_QUOTES); ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (!$isAuthor): ?>
                                        <p class="owner-help">Need to update users? Contact the development team.</p>
                                    <?php endif; ?>
                                </div>
                            </section>
                        </div>

                        <div class="owner-actions">
                            <button class="btn btn-primary owner-button" type="submit">Save Settings</button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
        <script>
            (function () {
                const tabs = Array.from(document.querySelectorAll('[data-tab]'));
                const panels = Array.from(document.querySelectorAll('[data-tab-panel]'));
                const actions = document.querySelector('.owner-actions');
                const accordions = Array.from(document.querySelectorAll('[data-accordion]'));
                const activeInput = document.querySelector('[data-active-tab]');
                const tabList = document.querySelector('.owner-tabs');
                let storedAccordionStates = {};

                if (!tabs.length || !panels.length) {
                    return;
                }

                try {
                    storedAccordionStates = JSON.parse(window.localStorage.getItem('ownerAccordionState') || '{}');
                } catch (err) {
                    storedAccordionStates = {};
                }

                const setActive = (tab) => {
                    tabs.forEach((button) => {
                        const isActive = button.dataset.tab === tab;
                        button.classList.toggle('is-active', isActive);
                        button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                        button.setAttribute('tabindex', isActive ? '0' : '-1');
                    });

                    panels.forEach((panel) => {
                        panel.hidden = panel.dataset.tabPanel !== tab;
                    });

                    if (actions) {
                        actions.hidden = tab === 'users';
                    }

                    if (activeInput) {
                        activeInput.value = tab;
                    }

                    try {
                        window.localStorage.setItem('ownerSettingsTab', tab);
                    } catch (err) {
                        // Ignore storage failures.
                    }

                    if (window.history && window.history.replaceState) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('tab', tab);
                        window.history.replaceState({}, '', url);
                    }
                };

                const params = new URLSearchParams(window.location.search);
                const requested = params.get('tab');
                let stored = null;
                try {
                    stored = window.localStorage.getItem('ownerSettingsTab');
                } catch (err) {
                    stored = null;
                }
                const defaultTab = tabList?.dataset.defaultTab;
                const initial =
                    tabs.find((button) => button.dataset.tab === requested)?.dataset.tab ||
                    tabs.find((button) => button.dataset.tab === stored)?.dataset.tab ||
                    tabs.find((button) => button.dataset.tab === defaultTab)?.dataset.tab ||
                    tabs[0].dataset.tab;

                setActive(initial);

                tabs.forEach((button) => {
                    button.addEventListener('click', () => {
                        setActive(button.dataset.tab);
                    });
                });

                accordions.forEach((accordion) => {
                    const toggle = accordion.querySelector('.owner-accordion-toggle');
                    const panel = accordion.querySelector('.owner-accordion-panel');
                    const indicator = accordion.querySelector('.owner-accordion-indicator');
                    const stateInput = accordion.querySelector('[data-accordion-state]');
                    const accordionKey = accordion.dataset.accordion;

                    if (!toggle || !panel) {
                        return;
                    }

                    const setOpen = (open, persist = true) => {
                        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                        panel.hidden = !open;
                        if (indicator) {
                            indicator.textContent = open ? 'Collapse' : 'Expand';
                        }
                        if (stateInput) {
                            stateInput.value = open ? '1' : '0';
                        }
                        if (persist && accordionKey) {
                            storedAccordionStates[accordionKey] = open;
                            try {
                                window.localStorage.setItem('ownerAccordionState', JSON.stringify(storedAccordionStates));
                            } catch (err) {
                                // Ignore storage failures.
                            }
                        }
                    };

                    const storedState = accordionKey ? storedAccordionStates[accordionKey] : null;
                    if (typeof storedState === 'boolean') {
                        setOpen(storedState, false);
                    } else {
                        setOpen(!panel.hidden, false);
                    }

                    toggle.addEventListener('click', () => {
                        setOpen(panel.hidden);
                    });
                });
            })();
        </script>
    </body>
</html>
