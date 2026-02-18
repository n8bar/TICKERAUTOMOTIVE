<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/settings.php';

owner_send_no_cache_headers();
owner_require_login();

$user = $_SESSION['auth'] ?? [];
$isOwner = ($user['role'] ?? '') === 'owner';
$isDeveloper = ($user['role'] ?? '') === 'developer';
$canManageAdmins = $isOwner || $isDeveloper;
$canManageAll = $isDeveloper;

$settings = owner_load_settings();
$errors = [];
$notices = [];
$flash = owner_get_flash();
$allowedTabs = ['general', 'forms', 'preview', 'users'];
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

$previewDir = __DIR__ . '/../preview/pages';
$previewPages = [];
if (is_dir($previewDir)) {
    $previewFiles = glob($previewDir . '/*.php') ?: [];
    foreach ($previewFiles as $file) {
        $basename = basename($file);
        $name = pathinfo($basename, PATHINFO_FILENAME);
        $label = ucwords(str_replace(['-', '_'], ' ', $name));
        $key = 'preview_' . preg_replace('/[^a-z0-9]+/i', '_', $name);
        $previewPages[] = [
            'key' => $key,
            'label' => $label,
            'url' => '/owner/preview/pages/' . rawurlencode($basename) . '?skip_sw_cache=1',
        ];
    }
    usort($previewPages, function (array $left, array $right): int {
        return strcasecmp($left['label'], $right['label']);
    });
}

$fieldLabels = [
    'name' => 'Full name',
    'phone' => 'Phone number',
    'email' => 'Email address',
    'service' => 'Service requested',
    'year' => 'Vehicle year',
    'make' => 'Vehicle make',
    'model' => 'Vehicle model',
    'engine' => 'Engine',
    'license_plate' => 'License plate',
    'license_plate_state' => 'State/Province/Territory',
    'vin' => 'VIN',
    'color' => 'Color',
    'color_code' => 'Color code',
    'unit_number' => 'Unit #',
    'production_date' => 'Production date',
    'preferred_time' => 'Preferred time',
    'message' => 'Message',
];

$formFieldOrder = owner_form_field_order();

$addAdminValues = [
    'name' => '',
    'email' => '',
];
$action = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!owner_verify_csrf(is_string($csrfToken) ? $csrfToken : null)) {
        $errors[] = 'Security check failed. Please try again.';
    } else {
        $action = $_POST['action'] ?? 'save_settings';
        if (!is_string($action) || $action === '') {
            $action = 'save_settings';
        }

        $activeTab = $_POST['active_tab'] ?? '';
        if (is_string($activeTab) && in_array($activeTab, $allowedTabs, true)) {
            $_SESSION['owner_active_tab'] = $activeTab;
            $requestedTab = $activeTab;
        }

        if ($action === 'save_settings') {
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
                $redirectTab = is_string($activeTab) && in_array($activeTab, $allowedTabs, true) ? $activeTab : 'general';
                owner_set_flash('success', 'Settings saved.');
                owner_redirect('/owner/settings/?tab=' . rawurlencode($redirectTab));
            } else {
                $errors[] = 'Unable to save settings. Check file permissions.';
            }
        }

        if ($action === 'add_admin') {
            if (!$canManageAdmins) {
                $errors[] = 'Only owners or developers can add admin accounts.';
            } else {
                $userInput = $_POST['user_add'] ?? [];
                if (!is_array($userInput)) {
                    $userInput = [];
                }

                $name = owner_sanitize_text($userInput['name'] ?? '');
                $email = owner_normalize_email((string) ($userInput['email'] ?? ''));
                $password = (string) ($userInput['password'] ?? '');
                $passwordConfirm = (string) ($userInput['password_confirm'] ?? '');

                $addAdminValues['name'] = $name;
                $addAdminValues['email'] = $email;

                if ($name === '' || $email === '') {
                    $errors[] = 'Enter a name and email for the admin account.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid email address.';
                }

                if ($password === '' || $passwordConfirm === '') {
                    $errors[] = 'Enter and confirm a password for the new admin.';
                } elseif ($password !== $passwordConfirm) {
                    $errors[] = 'The admin passwords do not match.';
                } else {
                    $errors = array_merge($errors, owner_password_strength_errors($password));
                }

                if (owner_find_account($email)) {
                    $errors[] = 'An account already exists for that email.';
                }

                if (!$errors) {
                    $managedAccounts = owner_load_managed_accounts();
                    $managedAccounts[] = [
                        'email' => $email,
                        'name' => $name,
                        'role' => 'admin',
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'enabled' => true,
                        'created_at' => gmdate('c'),
                    ];

                    if (owner_save_managed_accounts($managedAccounts)) {
                        owner_set_flash('success', 'Admin account added.');
                        owner_redirect('/owner/settings/?tab=users');
                    } else {
                        $errors[] = 'Unable to save the admin account.';
                    }
                }
            }
        }

        if ($action === 'update_admin') {
            if (!$canManageAdmins) {
                $errors[] = 'Only owners or developers can edit admin accounts.';
            } else {
                $userUpdate = $_POST['user_update'] ?? [];
                if (!is_array($userUpdate)) {
                    $userUpdate = [];
                }

                $originalEmail = owner_normalize_email((string) ($userUpdate['original_email'] ?? ''));
                $name = owner_sanitize_text($userUpdate['name'] ?? '');
                $email = owner_normalize_email((string) ($userUpdate['email'] ?? ''));

                if ($originalEmail === '' || $email === '' || $name === '') {
                    $errors[] = 'Enter a name and email for the admin account.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid email address.';
                }

                if ($originalEmail !== '' && owner_is_base_account($originalEmail) && !$isDeveloper) {
                    $errors[] = 'Base admin accounts must be edited by a developer.';
                }

                if ($email !== '' && $email !== $originalEmail && owner_find_account($email)) {
                    $errors[] = 'Another account already uses that email address.';
                }

                if (!$errors) {
                    $existingAccount = owner_find_account($originalEmail);
                    if (!$existingAccount) {
                        $errors[] = 'Admin account not found.';
                    } elseif (($existingAccount['role'] ?? 'admin') !== 'admin') {
                        $errors[] = 'Only admin accounts can be edited here.';
                    }
                }

                if (!$errors) {
                    $managedAccounts = owner_load_managed_accounts();
                    $isBaseAccount = owner_is_base_account($originalEmail);
                    $updated = false;
                    foreach ($managedAccounts as &$account) {
                        if (owner_account_key($account['email'] ?? '') === $originalEmail) {
                            $account['name'] = $name;
                            $account['email'] = $email;
                            $account['enabled'] = true;
                            if ($isBaseAccount) {
                                $account['override'] = true;
                            }
                            if ($email !== $originalEmail) {
                                $account['previous_email'] = $originalEmail;
                            }
                            $account['updated_at'] = gmdate('c');
                            $updated = true;
                            break;
                        }
                    }
                    unset($account);

                    if (!$errors && !$updated) {
                        $passwordHash = (string) ($existingAccount['password_hash'] ?? '');
                        if ($passwordHash === '') {
                            $errors[] = 'Unable to update the admin account.';
                        } else {
                            $newEntry = [
                                'email' => $email,
                                'name' => $name,
                                'role' => 'admin',
                                'password_hash' => $passwordHash,
                                'enabled' => true,
                                'override' => $isBaseAccount,
                                'updated_at' => gmdate('c'),
                            ];
                            if ($email !== $originalEmail) {
                                $newEntry['previous_email'] = $originalEmail;
                            }
                            $managedAccounts[] = $newEntry;
                            $updated = true;
                        }
                    }

                    if (!$errors) {
                        if (owner_save_managed_accounts($managedAccounts)) {
                            owner_set_flash('success', 'Admin details updated.');
                            owner_redirect('/owner/settings/?tab=users');
                        } else {
                            $errors[] = 'Unable to update the admin account.';
                        }
                    }
                }
            }
        }

        if ($action === 'remove_admin') {
            if (!$canManageAdmins) {
                $errors[] = 'Only owners or developers can remove admin accounts.';
            } else {
                $userRemove = $_POST['user_remove'] ?? [];
                if (!is_array($userRemove)) {
                    $userRemove = [];
                }
                $email = owner_normalize_email((string) ($userRemove['email'] ?? ''));
                if ($email === '') {
                    $errors[] = 'Missing admin account details.';
                } elseif (owner_is_base_account($email)) {
                    $errors[] = 'Base accounts cannot be removed here.';
                } else {
                    $managedAccounts = owner_load_managed_accounts();
                    $nextAccounts = [];
                    $removed = false;
                    foreach ($managedAccounts as $account) {
                        if (owner_account_key($account['email'] ?? '') === $email) {
                            if (($account['role'] ?? 'admin') !== 'admin') {
                                $errors[] = 'Only admin accounts can be removed here.';
                                $nextAccounts[] = $account;
                            } else {
                                $removed = true;
                            }
                            continue;
                        }
                        $nextAccounts[] = $account;
                    }

                    if (!$errors && !$removed) {
                        $errors[] = 'Admin account not found.';
                    }

                    if (!$errors) {
                        if (owner_save_managed_accounts($nextAccounts)) {
                            owner_set_flash('success', 'Admin access removed.');
                            owner_redirect('/owner/settings/?tab=users');
                        } else {
                            $errors[] = 'Unable to update admin accounts.';
                        }
                    }
                }
            }
        }

        if ($action === 'reset_admin_password') {
            if (!$canManageAdmins) {
                $errors[] = 'Only owners or developers can reset admin passwords.';
            } else {
                $userReset = $_POST['user_reset'] ?? [];
                if (!is_array($userReset)) {
                    $userReset = [];
                }
                $email = owner_normalize_email((string) ($userReset['email'] ?? ''));
                $password = (string) ($userReset['password'] ?? '');
                $passwordConfirm = (string) ($userReset['password_confirm'] ?? '');

                if ($email === '') {
                    $errors[] = 'Missing admin account details.';
                }
                if ($password === '' || $passwordConfirm === '') {
                    $errors[] = 'Enter and confirm the new admin password.';
                } elseif ($password !== $passwordConfirm) {
                    $errors[] = 'The admin passwords do not match.';
                } else {
                    $errors = array_merge($errors, owner_password_strength_errors($password));
                }

                if (!$errors) {
                    $existingAccount = owner_find_account($email);
                    if (!$existingAccount) {
                        $errors[] = 'Admin account not found.';
                    } elseif (($existingAccount['role'] ?? 'admin') !== 'admin') {
                        $errors[] = 'Only admin accounts can be updated here.';
                    } elseif (owner_is_base_account($email) && !$isDeveloper) {
                        $errors[] = 'Base admin accounts must be edited by a developer.';
                    }
                }

                if (!$errors) {
                    $managedAccounts = owner_load_managed_accounts();
                    $updated = false;
                    foreach ($managedAccounts as &$account) {
                        if (owner_account_key($account['email'] ?? '') === $email) {
                            $account['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                            $account['enabled'] = true;
                            $account['override'] = owner_is_base_account($email) || !empty($account['override']);
                            $account['updated_at'] = gmdate('c');
                            $updated = true;
                            break;
                        }
                    }
                    unset($account);

                    if (!$errors && !$updated) {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        $managedAccounts[] = [
                            'email' => $email,
                            'name' => $existingAccount['name'] ?? 'Admin',
                            'role' => 'admin',
                            'password_hash' => $passwordHash,
                            'enabled' => true,
                            'override' => owner_is_base_account($email),
                            'updated_at' => gmdate('c'),
                        ];
                        $updated = true;
                    }

                    if (!$errors) {
                        if (owner_save_managed_accounts($managedAccounts)) {
                            owner_set_flash('success', 'Admin password updated.');
                            owner_redirect('/owner/settings/?tab=users');
                        } else {
                            $errors[] = 'Unable to update the admin password.';
                        }
                    }
                }
            }
        }

        if ($action === 'change_password') {
            $userPassword = $_POST['user_password'] ?? [];
            if (!is_array($userPassword)) {
                $userPassword = [];
            }
            $currentPassword = (string) ($userPassword['current'] ?? '');
            $newPassword = (string) ($userPassword['new'] ?? '');
            $confirmPassword = (string) ($userPassword['confirm'] ?? '');

            if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                $errors[] = 'Fill out all password fields.';
            } elseif (($user['email'] ?? '') === '' || !owner_verify_credentials($user['email'], $currentPassword)) {
                $errors[] = 'Current password is incorrect.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'New passwords do not match.';
            } else {
                $errors = array_merge($errors, owner_password_strength_errors($newPassword));
            }

            if (!$errors) {
                $managedAccounts = owner_load_managed_accounts();
                $email = owner_account_key($user['email'] ?? '');
                $updated = false;
                foreach ($managedAccounts as &$account) {
                    if (owner_account_key($account['email'] ?? '') === $email) {
                        $account['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
                        $account['enabled'] = true;
                        if (owner_is_base_account($email)) {
                            $account['override'] = true;
                        }
                        $updated = true;
                        break;
                    }
                }
                unset($account);

                if (!$updated) {
                    $currentAccount = owner_find_account($email) ?? [];
                    $managedAccounts[] = [
                        'email' => $email,
                        'name' => $currentAccount['name'] ?? ($user['name'] ?? 'Admin'),
                        'role' => $currentAccount['role'] ?? ($user['role'] ?? 'admin'),
                        'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
                        'enabled' => true,
                        'override' => owner_is_base_account($email),
                        'updated_at' => gmdate('c'),
                    ];
                }

                if (owner_save_managed_accounts($managedAccounts)) {
                    owner_set_flash('success', 'Password updated.');
                    owner_redirect('/owner/settings/?tab=users');
                } else {
                    $errors[] = 'Unable to update your password.';
                }
            }
        }

        if ($action === 'change_email') {
            $userEmail = $_POST['user_email'] ?? [];
            if (!is_array($userEmail)) {
                $userEmail = [];
            }
            $currentPassword = (string) ($userEmail['current'] ?? '');
            $newEmail = owner_normalize_email((string) ($userEmail['new'] ?? ''));
            $confirmEmail = owner_normalize_email((string) ($userEmail['confirm'] ?? ''));
            $currentEmail = owner_account_key($user['email'] ?? '');

            if ($currentPassword === '' || $newEmail === '' || $confirmEmail === '') {
                $errors[] = 'Fill out all login email fields.';
            } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Enter a valid email address.';
            } elseif ($newEmail !== $confirmEmail) {
                $errors[] = 'Login emails do not match.';
            } elseif ($currentEmail === '' || !owner_verify_credentials($currentEmail, $currentPassword)) {
                $errors[] = 'Current password is incorrect.';
            } elseif ($newEmail === $currentEmail) {
                $errors[] = 'That email is already your login.';
            } elseif (owner_find_account($newEmail)) {
                $errors[] = 'Another account already uses that email address.';
            }

            if (!$errors) {
                $currentAccount = owner_find_account($currentEmail);
                if (!$currentAccount) {
                    $errors[] = 'Account not found.';
                }
            }

            if (!$errors) {
                $managedAccounts = owner_load_managed_accounts();
                $updated = false;
                foreach ($managedAccounts as &$account) {
                    if (owner_account_key($account['email'] ?? '') === $currentEmail) {
                        $account['email'] = $newEmail;
                        $account['enabled'] = true;
                        $account['previous_email'] = $currentEmail;
                        if (owner_is_base_account($currentEmail)) {
                            $account['override'] = true;
                        }
                        $account['updated_at'] = gmdate('c');
                        $updated = true;
                        break;
                    }
                }
                unset($account);

                if (!$updated) {
                    $passwordHash = (string) ($currentAccount['password_hash'] ?? '');
                    if ($passwordHash === '') {
                        $errors[] = 'Unable to update your login email.';
                    } else {
                        $managedAccounts[] = [
                            'email' => $newEmail,
                            'name' => $currentAccount['name'] ?? ($user['name'] ?? 'Admin'),
                            'role' => $currentAccount['role'] ?? ($user['role'] ?? 'admin'),
                            'password_hash' => $passwordHash,
                            'enabled' => true,
                            'override' => owner_is_base_account($currentEmail),
                            'previous_email' => $currentEmail,
                            'updated_at' => gmdate('c'),
                        ];
                    }
                }

                if (!$errors) {
                    if (owner_save_managed_accounts($managedAccounts)) {
                        $_SESSION['auth']['email'] = $newEmail;
                        owner_set_flash('success', 'Login email updated.');
                        owner_redirect('/owner/settings/?tab=users');
                    } else {
                        $errors[] = 'Unable to update your login email.';
                    }
                }
            }
        }

        if ($action === 'update_account') {
            if (!$canManageAll) {
                $errors[] = 'Only developers can edit accounts.';
            } else {
                $userUpdate = $_POST['user_update_all'] ?? [];
                if (!is_array($userUpdate)) {
                    $userUpdate = [];
                }

                $originalEmail = owner_normalize_email((string) ($userUpdate['original_email'] ?? ''));
                $name = owner_sanitize_text($userUpdate['name'] ?? '');
                $email = owner_normalize_email((string) ($userUpdate['email'] ?? ''));

                if ($originalEmail === '' || $email === '' || $name === '') {
                    $errors[] = 'Enter a name and email for the account.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid email address.';
                } elseif ($email !== $originalEmail && owner_find_account($email)) {
                    $errors[] = 'Another account already uses that email address.';
                }

                if (!$errors) {
                    $existingAccount = owner_find_account($originalEmail);
                    if (!$existingAccount) {
                        $errors[] = 'Account not found.';
                    }
                }

                if (!$errors) {
                    $managedAccounts = owner_load_managed_accounts();
                    $isBaseAccount = owner_is_base_account($originalEmail);
                    $updated = false;
                    foreach ($managedAccounts as &$account) {
                        if (owner_account_key($account['email'] ?? '') === $originalEmail) {
                            $account['name'] = $name;
                            $account['email'] = $email;
                            $account['enabled'] = true;
                            if ($isBaseAccount) {
                                $account['override'] = true;
                            }
                            if ($email !== $originalEmail) {
                                $account['previous_email'] = $originalEmail;
                            }
                            $account['updated_at'] = gmdate('c');
                            $updated = true;
                            break;
                        }
                    }
                    unset($account);

                    if (!$updated) {
                        $passwordHash = (string) ($existingAccount['password_hash'] ?? '');
                        if ($passwordHash === '') {
                            $errors[] = 'Unable to update the account.';
                        } else {
                            $newEntry = [
                                'email' => $email,
                                'name' => $name,
                                'role' => $existingAccount['role'] ?? 'admin',
                                'password_hash' => $passwordHash,
                                'enabled' => true,
                                'override' => $isBaseAccount,
                                'updated_at' => gmdate('c'),
                            ];
                            if ($email !== $originalEmail) {
                                $newEntry['previous_email'] = $originalEmail;
                            }
                            $managedAccounts[] = $newEntry;
                        }
                    }

                    if (!$errors) {
                        if (owner_save_managed_accounts($managedAccounts)) {
                            if ($originalEmail === owner_account_key($user['email'] ?? '')) {
                                $_SESSION['auth']['email'] = $email;
                            }
                            owner_set_flash('success', 'Account updated.');
                            owner_redirect('/owner/settings/?tab=users');
                        } else {
                            $errors[] = 'Unable to update the account.';
                        }
                    }
                }
            }
        }

        if ($action === 'reset_account_password') {
            if (!$canManageAll) {
                $errors[] = 'Only developers can reset passwords.';
            } else {
                $userReset = $_POST['user_reset'] ?? [];
                if (!is_array($userReset)) {
                    $userReset = [];
                }
                $email = owner_normalize_email((string) ($userReset['email'] ?? ''));
                $password = (string) ($userReset['password'] ?? '');
                $passwordConfirm = (string) ($userReset['password_confirm'] ?? '');

                if ($email === '') {
                    $errors[] = 'Missing account details.';
                }
                if ($password === '' || $passwordConfirm === '') {
                    $errors[] = 'Enter and confirm the new password.';
                } elseif ($password !== $passwordConfirm) {
                    $errors[] = 'The passwords do not match.';
                } else {
                    $errors = array_merge($errors, owner_password_strength_errors($password));
                }

                if (!$errors) {
                    $existingAccount = owner_find_account($email);
                    if (!$existingAccount) {
                        $errors[] = 'Account not found.';
                    }
                }

                if (!$errors) {
                    $managedAccounts = owner_load_managed_accounts();
                    $updated = false;
                    foreach ($managedAccounts as &$account) {
                        if (owner_account_key($account['email'] ?? '') === $email) {
                            $account['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                            $account['enabled'] = true;
                            if (owner_is_base_account($email)) {
                                $account['override'] = true;
                            }
                            $account['updated_at'] = gmdate('c');
                            $updated = true;
                            break;
                        }
                    }
                    unset($account);

                    if (!$updated) {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        $managedAccounts[] = [
                            'email' => $email,
                            'name' => $existingAccount['name'] ?? 'User',
                            'role' => $existingAccount['role'] ?? 'admin',
                            'password_hash' => $passwordHash,
                            'enabled' => true,
                            'override' => owner_is_base_account($email),
                            'updated_at' => gmdate('c'),
                        ];
                    }

                    if (owner_save_managed_accounts($managedAccounts)) {
                        owner_set_flash('success', 'Password updated.');
                        owner_redirect('/owner/settings/?tab=users');
                    } else {
                        $errors[] = 'Unable to update the password.';
                    }
                }
            }
        }
    }
}

$csrfToken = owner_csrf_token();
$config = owner_get_config();
$accounts = owner_load_accounts();
$managedAccounts = owner_load_managed_accounts();
$roleOrder = ['owner', 'admin'];

if ($isDeveloper) {
    $roleOrder[] = 'developer';
}

$roleLabels = [
    'owner' => 'Owner',
    'admin' => 'Admin',
    'developer' => 'Developer',
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

    if ($role === 'developer' && !$isDeveloper) {
        continue;
    }

    if (!$isDeveloper && !empty($account['hidden'])) {
        continue;
    }

    $accountsByRole[$role][] = $account;
}

$managedAdmins = [];
foreach ($managedAccounts as $account) {
    if (empty($account['enabled'])) {
        continue;
    }
    if (($account['role'] ?? 'admin') !== 'admin') {
        continue;
    }
    $managedAdmins[] = $account;
}

$managedAdminIndex = [];
foreach ($managedAdmins as $admin) {
    $email = owner_account_key($admin['email'] ?? '');
    if ($email !== '') {
        $managedAdminIndex[$email] = $admin;
    }
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
        <link rel="icon" type="image/svg+xml" href="/owner/favicons/14-sliders.svg" sizes="any">
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

                    <div class="owner-tabs" role="tablist" aria-label="Settings sections" data-default-tab="<?php echo htmlspecialchars($requestedTab, ENT_QUOTES); ?>">
                        <button class="owner-tab is-active" type="button" data-tab="general" role="tab" aria-selected="true" aria-controls="tab-general" id="tab-general-button">General</button>
                        <button class="owner-tab" type="button" data-tab="forms" role="tab" aria-selected="false" aria-controls="tab-forms" id="tab-forms-button">Forms</button>
                        <button class="owner-tab" type="button" data-tab="users" role="tab" aria-selected="false" aria-controls="tab-users" id="tab-users-button">Users</button>
                        <button class="owner-tab" type="button" data-tab="preview" role="tab" aria-selected="false" aria-controls="tab-preview" id="tab-preview-button">Feature Previews</button>
                    </div>

                    <div class="owner-tab-panels">
                        <form class="owner-form" method="post" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                            <input type="hidden" name="active_tab" value="<?php echo htmlspecialchars($requestedTab, ENT_QUOTES); ?>" data-active-tab>

                            <section class="owner-tab-panel" data-tab-panel="general" role="tabpanel" aria-labelledby="tab-general-button" id="tab-general">
                                <br />
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
                                <?php if ($isDeveloper): ?>
                                    <?php
                                        $deliveryOverride = $settings['contact_forms']['delivery_override'] ?? [];
                                        $overrideEnabled = !empty($deliveryOverride['enabled']);
                                        $overrideEmail = $deliveryOverride['email'] ?? '';
                                        $httpDelivery = $settings['contact_forms']['http_delivery'] ?? [];
                                        $httpEnabled = !empty($httpDelivery['enabled']);
                                    ?>
                                    <div class="owner-panel">
                                        <h2 class="owner-panel-title">Developer Delivery Override</h2>
                                        <p class="owner-help">Route all form submissions to the developer inbox for testing. This does not change the public recipients.</p>
                                        <label class="owner-toggle">
                                            <input type="checkbox" name="settings[contact_forms][delivery_override][enabled]" value="1"<?php echo owner_checked($overrideEnabled); ?>>
                                            <span>Send form submissions to developer email</span>
                                        </label>
                                        <label class="owner-field">
                                            <span class="owner-label">Developer email</span>
                                            <input class="owner-input" type="email" name="settings[contact_forms][delivery_override][email]" value="<?php echo htmlspecialchars($overrideEmail, ENT_QUOTES); ?>">
                                        </label>
                                    </div>
                                    <div class="owner-panel">
                                        <h2 class="owner-panel-title">Developer HTTP Delivery</h2>
                                        <p class="owner-help">Send form emails via the Mailgun HTTP API instead of SMTP. Configure the Mailgun API key and domain directly in <code>owner/data/settings.json</code>.</p>
                                        <label class="owner-toggle">
                                            <input type="checkbox" name="settings[contact_forms][http_delivery][enabled]" value="1"<?php echo owner_checked($httpEnabled); ?>>
                                            <span>Send via Mailgun HTTP API</span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <div class="owner-accordion-grid">
                                    <?php foreach ($formLabels as $formKey => $formLabel): ?>
                                        <?php
                                            $formSettings = $settings['contact_forms'][$formKey] ?? [];
                                            $recipients = $formSettings['recipients'] ?? [];
                                            $recipientValue = implode(', ', $recipients);
                                            $fields = $formSettings['fields'] ?? [];
                                            $autoReply = $formSettings['auto_reply'] ?? [];
                                            $emailState = $fields['email'] ?? ['enabled' => false, 'required' => false];
                                            $emailEnabled = !empty($emailState['enabled']);
                                            $emailRequired = !empty($emailState['required']);
                                            $autoReplyNeedsEmail = !empty($autoReply['enabled']) && (!$emailEnabled || !$emailRequired);
                                            $isOpen = !empty($accordionState[$formKey]);
                                        ?>
                                        <div class="owner-accordion" data-accordion="<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>">
                                            <div class="owner-accordion-header">
                                                <button class="owner-accordion-toggle" type="button" aria-expanded="<?php echo $isOpen ? 'true' : 'false'; ?>" aria-controls="form-<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>" id="form-toggle-<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>">
                                                    <span class="owner-accordion-indicator" title="<?php echo $isOpen ? 'Collapse' : 'Expand'; ?>" aria-hidden="true"></span>
                                                    <span class="owner-accordion-title"><?php echo htmlspecialchars($formLabel, ENT_QUOTES); ?> Form</span>
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
                                                <p class="owner-help">Tip: Require only the essentials (name/phone). Optional details like VIN or color code can frustrate customers if required.</p>
                                                <div class="owner-fields-grid">
                                                    <?php $allowedFields = $formFieldOrder[$formKey] ?? array_keys($fieldLabels); ?>
                                                        <?php foreach ($allowedFields as $fieldKey): ?>
                                                            <?php
                                                                $fieldLabel = $fieldLabels[$fieldKey] ?? ucfirst(str_replace('_', ' ', $fieldKey));
                                                                $fieldState = $fields[$fieldKey] ?? ['enabled' => false, 'required' => false];
                                                            ?>
                                                            <div class="owner-field-row<?php echo !empty($fieldState['enabled']) ? '' : ' is-required-disabled'; ?>" data-field-row data-field-key="<?php echo htmlspecialchars($fieldKey, ENT_QUOTES); ?>">
                                                                <div class="owner-field-meta">
                                                                    <span class="owner-field-name"><?php echo htmlspecialchars($fieldLabel, ENT_QUOTES); ?></span>
                                                                </div>
                                                                <label class="owner-toggle">
                                                                    <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][fields][<?php echo htmlspecialchars($fieldKey, ENT_QUOTES); ?>][enabled]" value="1" data-field-toggle="show"<?php echo owner_checked(!empty($fieldState['enabled'])); ?>>
                                                                    <span>Show</span>
                                                                </label>
                                                                <label class="owner-toggle">
                                                                    <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][fields][<?php echo htmlspecialchars($fieldKey, ENT_QUOTES); ?>][required]" value="1" data-field-toggle="required"<?php echo owner_checked(!empty($fieldState['required'])); ?><?php echo !empty($fieldState['enabled']) ? '' : ' disabled'; ?>>
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
                                                            <input type="checkbox" name="settings[contact_forms][<?php echo htmlspecialchars($formKey, ENT_QUOTES); ?>][auto_reply][enabled]" value="1" data-auto-reply-toggle<?php echo owner_checked(!empty($autoReply['enabled'])); ?>>
                                                            <span>Send auto-reply</span>
                                                        </label>
                                                        <p class="owner-help owner-help-warning" data-auto-reply-note<?php echo $autoReplyNeedsEmail ? '' : ' hidden'; ?>>
                                                            Auto-reply sends to the email provided on the form. Make sure Email is shown and consider making it required.
                                                        </p>
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
                                </div>
                            </section>

                            <section class="owner-tab-panel" data-tab-panel="preview" role="tabpanel" aria-labelledby="tab-preview-button" id="tab-preview" hidden>
                                <br />
                                <div class="owner-section">
                                    <h2 class="owner-section-title">Feature Previews</h2>
                                    <p class="owner-subtitle">Review in-progress pages before they go live.</p>
                                    <?php if (empty($previewPages)): ?>
                                        <p class="owner-help">No preview pages found in <code>/owner/preview/pages</code>.</p>
                                    <?php else: ?>
                                        <div class="owner-preview-accordions">
                                            <?php foreach ($previewPages as $preview): ?>
                                                <div class="owner-accordion owner-preview-accordion" data-accordion="<?php echo htmlspecialchars($preview['key'], ENT_QUOTES); ?>">
                                                    <div class="owner-accordion-header">
                                                        <button class="owner-accordion-toggle" type="button" aria-expanded="false" aria-controls="preview-<?php echo htmlspecialchars($preview['key'], ENT_QUOTES); ?>" id="preview-toggle-<?php echo htmlspecialchars($preview['key'], ENT_QUOTES); ?>">
                                                            <span class="owner-accordion-indicator" title="Expand" aria-hidden="true"></span>
                                                            <span class="owner-accordion-title"><?php echo htmlspecialchars($preview['label'], ENT_QUOTES); ?></span>
                                                        </button>
                                                    </div>
                                                    <div class="owner-accordion-panel" id="preview-<?php echo htmlspecialchars($preview['key'], ENT_QUOTES); ?>" role="region" aria-labelledby="preview-toggle-<?php echo htmlspecialchars($preview['key'], ENT_QUOTES); ?>" hidden>
                                                        <div class="owner-preview-frame">
                                                            <iframe class="owner-preview-iframe" src="<?php echo htmlspecialchars($preview['url'], ENT_QUOTES); ?>" title="<?php echo htmlspecialchars($preview['label'], ENT_QUOTES); ?> preview" loading="lazy"></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </section>

                            <div class="owner-actions">
                                <button class="btn btn-primary owner-button" type="submit" name="action" value="save_settings">Save Settings</button>
                            </div>
                            <div class="owner-actions-meta" data-last-login hidden>
                                <?php
                                    $lastLogin = owner_get_last_login_time((string) ($user['email'] ?? ''));
                                    $lastLoginIso = $lastLogin ? gmdate('c', $lastLogin) : '';
                                    $lastLoginLabel = $lastLogin ? date('M j, Y g:i A', $lastLogin) : 'Unavailable';
                                ?>
                                <p class="owner-help">Last login: <span data-last-login-time="<?php echo htmlspecialchars($lastLoginIso, ENT_QUOTES); ?>"><?php echo htmlspecialchars($lastLoginLabel, ENT_QUOTES); ?></span></p>
                            </div>
                        </form>

                        <section class="owner-tab-panel" data-tab-panel="users" role="tabpanel" aria-labelledby="tab-users-button" id="tab-users" hidden>
                            <div class="owner-accordion-grid">
                            <?php $usersOverviewOpen = true; ?>
                            <div class="owner-accordion" data-accordion="users_overview">
                                <div class="owner-accordion-header">
                                    <button class="owner-accordion-toggle" type="button" aria-expanded="<?php echo $usersOverviewOpen ? 'true' : 'false'; ?>" aria-controls="users-overview-panel" id="users-overview-toggle">
                                        <span class="owner-accordion-indicator" title="<?php echo $usersOverviewOpen ? 'Collapse' : 'Expand'; ?>" aria-hidden="true"></span>
                                        <span class="owner-accordion-title">Users Overview</span>
                                    </button>
                                </div>
                                <div class="owner-accordion-panel" id="users-overview-panel" role="region" aria-labelledby="users-overview-toggle"<?php echo $usersOverviewOpen ? '' : ' hidden'; ?>>
                                    <div class="owner-section owner-section-compact">
                                        <p class="owner-subtitle owner-subtitle-tight">Manage who can access the owner portal.</p>
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
                                                    <?php if ($role === 'owner' && !$isDeveloper): ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if (!$canManageAdmins): ?>
                                            <p class="owner-help">Only owners or developers can manage users.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php $adminAccessOpen = false; ?>
                            <div class="owner-accordion" data-accordion="users_admin_access">
                                <div class="owner-accordion-header">
                                    <button class="owner-accordion-toggle" type="button" aria-expanded="<?php echo $adminAccessOpen ? 'true' : 'false'; ?>" aria-controls="users-admin-panel" id="users-admin-toggle">
                                        <span class="owner-accordion-indicator" title="<?php echo $adminAccessOpen ? 'Collapse' : 'Expand'; ?>" aria-hidden="true"></span>
                                        <span class="owner-accordion-title">Admin Access</span>
                                    </button>
                                </div>
                                <div class="owner-accordion-panel" id="users-admin-panel" role="region" aria-labelledby="users-admin-toggle"<?php echo $adminAccessOpen ? '' : ' hidden'; ?>>
                                    <div class="owner-section owner-section-compact">
                                        <p class="owner-subtitle owner-subtitle-tight">Add, edit, or remove admin logins.</p>
                                        <?php if (!$canManageAdmins): ?>
                                            <p class="owner-help">Only owners or developers can manage admin accounts.</p>
                                        <?php else: ?>
                                            <div class="owner-grid">
                                                <div class="owner-panel">
                                                    <h3 class="owner-panel-title">Add an admin</h3>
                                                    <form class="owner-form" method="post" action="">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                                        <input type="hidden" name="action" value="add_admin">
                                                        <input type="hidden" name="active_tab" value="users" data-active-tab>
                                                        <label class="owner-field">
                                                            <span class="owner-label">Name</span>
                                                            <input class="owner-input" type="text" name="user_add[name]" value="<?php echo htmlspecialchars($addAdminValues['name'], ENT_QUOTES); ?>" placeholder="Admin name">
                                                        </label>
                                                        <label class="owner-field">
                                                            <span class="owner-label">Email</span>
                                                            <input class="owner-input" type="email" name="user_add[email]" value="<?php echo htmlspecialchars($addAdminValues['email'], ENT_QUOTES); ?>" placeholder="admin@email.com">
                                                        </label>
                                                        <div class="owner-password-panel" data-password-group>
                                                            <label class="owner-field">
                                                                <span class="owner-label">Password</span>
                                                                <input class="owner-input" type="password" name="user_add[password]" autocomplete="new-password" placeholder="At least 12 characters" data-password-input="primary">
                                                            </label>
                                                            <label class="owner-field">
                                                                <span class="owner-label">Confirm password</span>
                                                                <input class="owner-input" type="password" name="user_add[password_confirm]" autocomplete="new-password" placeholder="Repeat password" data-password-input="confirm">
                                                            </label>
                                                            <div class="owner-password-actions">
                                                                <button class="owner-link-button" type="button" data-password-generate>Generate strong password</button>
                                                                <button class="owner-link-button" type="button" data-password-toggle>Show</button>
                                                                <button class="owner-link-button" type="button" data-password-copy>Copy</button>
                                                            </div>
                                                            <span class="owner-help owner-password-status" data-password-status aria-live="polite"></span>
                                                        </div>
                                                        <p class="owner-help">Passwords must be at least 12 characters and include three of: uppercase, lowercase, number, symbol.</p>
                                                        <button class="btn btn-primary owner-button-inline" type="submit">Add Admin</button>
                                                    </form>
                                                </div>

                                                <div class="owner-panel">
                                                    <h3 class="owner-panel-title">Manage admins</h3>
                                                    <?php $adminAccounts = $accountsByRole['admin'] ?? []; ?>
                                                    <?php if (empty($adminAccounts)): ?>
                                                        <p class="owner-help">No admin accounts yet.</p>
                                                    <?php else: ?>
                                                        <div class="owner-admin-list">
                                                            <?php foreach ($adminAccounts as $admin): ?>
                                                                <?php
                                                                    $adminEmail = owner_account_key($admin['email'] ?? '');
                                                                    $isManaged = isset($managedAdminIndex[$adminEmail]);
                                                                    $canEditAdmin = $isManaged || $isDeveloper;
                                                                    $isBaseAdmin = $adminEmail !== '' && owner_is_base_account($adminEmail);
                                                                ?>
                                                                <div class="owner-admin-card">
                                                                    <div class="owner-admin-meta">
                                                                        <span class="owner-user-entry-name"><?php echo htmlspecialchars($admin['name'] ?? 'Admin', ENT_QUOTES); ?></span>
                                                                        <span class="owner-user-entry-email"><?php echo htmlspecialchars($admin['email'] ?? '', ENT_QUOTES); ?></span>
                                                                    </div>
                                                                    <?php if ($canEditAdmin): ?>
                                                                        <form class="owner-form owner-form-compact" method="post" action="">
                                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                                                            <input type="hidden" name="action" value="update_admin">
                                                                            <input type="hidden" name="active_tab" value="users" data-active-tab>
                                                                            <input type="hidden" name="user_update[original_email]" value="<?php echo htmlspecialchars($admin['email'] ?? '', ENT_QUOTES); ?>">
                                                                            <label class="owner-field">
                                                                                <span class="owner-label">Name</span>
                                                                                <input class="owner-input" type="text" name="user_update[name]" value="<?php echo htmlspecialchars($admin['name'] ?? '', ENT_QUOTES); ?>">
                                                                            </label>
                                                                            <label class="owner-field">
                                                                                <span class="owner-label">Email</span>
                                                                                <input class="owner-input" type="email" name="user_update[email]" value="<?php echo htmlspecialchars($admin['email'] ?? '', ENT_QUOTES); ?>">
                                                                            </label>
                                                                            <button class="btn btn-primary owner-button-inline" type="submit">Save Changes</button>
                                                                        </form>
                                                                        <form class="owner-form" method="post" action="">
                                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                                                            <input type="hidden" name="action" value="reset_admin_password">
                                                                            <input type="hidden" name="active_tab" value="users" data-active-tab>
                                                                            <input type="hidden" name="user_reset[email]" value="<?php echo htmlspecialchars($admin['email'] ?? '', ENT_QUOTES); ?>">
                                                                            <div class="owner-password-panel" data-password-group>
                                                                                <label class="owner-field">
                                                                                    <span class="owner-label">New password</span>
                                                                                    <input class="owner-input" type="password" name="user_reset[password]" autocomplete="new-password" placeholder="At least 12 characters" data-password-input="primary">
                                                                                </label>
                                                                                <label class="owner-field">
                                                                                    <span class="owner-label">Confirm password</span>
                                                                                    <input class="owner-input" type="password" name="user_reset[password_confirm]" autocomplete="new-password" placeholder="Repeat password" data-password-input="confirm">
                                                                                </label>
                                                                                <div class="owner-password-actions">
                                                                                    <button class="owner-link-button" type="button" data-password-generate>Generate strong password</button>
                                                                                    <button class="owner-link-button" type="button" data-password-toggle>Show</button>
                                                                                    <button class="owner-link-button" type="button" data-password-copy>Copy</button>
                                                                                </div>
                                                                                <span class="owner-help owner-password-status" data-password-status aria-live="polite"></span>
                                                                            </div>
                                                                            <button class="btn btn-primary owner-button-inline" type="submit">Reset Password</button>
                                                                        </form>
                                                                        <?php if (!$isBaseAdmin): ?>
                                                                            <form class="owner-form owner-form-inline" method="post" action="">
                                                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                                                                <input type="hidden" name="action" value="remove_admin">
                                                                                <input type="hidden" name="active_tab" value="users" data-active-tab>
                                                                                <input type="hidden" name="user_remove[email]" value="<?php echo htmlspecialchars($admin['email'] ?? '', ENT_QUOTES); ?>">
                                                                                <button class="owner-link-button owner-link-danger" type="submit" data-confirm="Remove access for this admin?">Remove access</button>
                                                                            </form>
                                                                        <?php else: ?>
                                                                            <p class="owner-help">Base admin accounts cannot be removed here.</p>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <p class="owner-help">This admin is managed in configuration and cannot be edited here.</p>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($isDeveloper): ?>
                                <?php $specialAccountsOpen = false; ?>
                                <div class="owner-accordion" data-accordion="users_special_accounts">
                                    <div class="owner-accordion-header">
                                        <button class="owner-accordion-toggle" type="button" aria-expanded="<?php echo $specialAccountsOpen ? 'true' : 'false'; ?>" aria-controls="users-special-panel" id="users-special-toggle">
                                            <span class="owner-accordion-indicator" title="<?php echo $specialAccountsOpen ? 'Collapse' : 'Expand'; ?>" aria-hidden="true"></span>
                                            <span class="owner-accordion-title">Owner &amp; Developer Accounts</span>
                                        </button>
                                    </div>
                                    <div class="owner-accordion-panel" id="users-special-panel" role="region" aria-labelledby="users-special-toggle"<?php echo $specialAccountsOpen ? '' : ' hidden'; ?>>
                                        <div class="owner-section owner-section-compact">
                                            <p class="owner-subtitle owner-subtitle-tight">Developers can edit all accounts.</p>
                                            <?php $specialAccounts = array_merge($accountsByRole['owner'] ?? [], $accountsByRole['developer'] ?? []); ?>
                                            <?php if (empty($specialAccounts)): ?>
                                                <p class="owner-help">No owner or developer accounts found.</p>
                                            <?php else: ?>
                                                <div class="owner-admin-list">
                                                    <?php foreach ($specialAccounts as $account): ?>
                                                        <div class="owner-admin-card">
                                                            <div class="owner-admin-meta">
                                                                <span class="owner-user-entry-name"><?php echo htmlspecialchars($account['name'] ?? 'User', ENT_QUOTES); ?></span>
                                                                <span class="owner-user-entry-email"><?php echo htmlspecialchars($account['email'] ?? '', ENT_QUOTES); ?></span>
                                                            </div>
                                                            <p class="owner-help"><?php echo htmlspecialchars($roleLabels[$account['role'] ?? 'admin'] ?? 'User', ENT_QUOTES); ?> account</p>
                                                            <form class="owner-form owner-form-compact" method="post" action="">
                                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                                                <input type="hidden" name="action" value="update_account">
                                                                <input type="hidden" name="active_tab" value="users" data-active-tab>
                                                                <input type="hidden" name="user_update_all[original_email]" value="<?php echo htmlspecialchars($account['email'] ?? '', ENT_QUOTES); ?>">
                                                                <label class="owner-field">
                                                                    <span class="owner-label">Name</span>
                                                                    <input class="owner-input" type="text" name="user_update_all[name]" value="<?php echo htmlspecialchars($account['name'] ?? '', ENT_QUOTES); ?>">
                                                                </label>
                                                                <label class="owner-field">
                                                                    <span class="owner-label">Email</span>
                                                                    <input class="owner-input" type="email" name="user_update_all[email]" value="<?php echo htmlspecialchars($account['email'] ?? '', ENT_QUOTES); ?>">
                                                                </label>
                                                                <button class="btn btn-primary owner-button-inline" type="submit">Save Changes</button>
                                                            </form>
                                                            <form class="owner-form" method="post" action="">
                                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                                                <input type="hidden" name="action" value="reset_account_password">
                                                                <input type="hidden" name="active_tab" value="users" data-active-tab>
                                                                <input type="hidden" name="user_reset[email]" value="<?php echo htmlspecialchars($account['email'] ?? '', ENT_QUOTES); ?>">
                                                                <div class="owner-password-panel" data-password-group>
                                                                    <label class="owner-field">
                                                                        <span class="owner-label">New password</span>
                                                                        <input class="owner-input" type="password" name="user_reset[password]" autocomplete="new-password" placeholder="At least 12 characters" data-password-input="primary">
                                                                    </label>
                                                                    <label class="owner-field">
                                                                        <span class="owner-label">Confirm password</span>
                                                                        <input class="owner-input" type="password" name="user_reset[password_confirm]" autocomplete="new-password" placeholder="Repeat password" data-password-input="confirm">
                                                                    </label>
                                                                    <div class="owner-password-actions">
                                                                        <button class="owner-link-button" type="button" data-password-generate>Generate strong password</button>
                                                                        <button class="owner-link-button" type="button" data-password-toggle>Show</button>
                                                                        <button class="owner-link-button" type="button" data-password-copy>Copy</button>
                                                                    </div>
                                                                    <span class="owner-help owner-password-status" data-password-status aria-live="polite"></span>
                                                                </div>
                                                                <button class="btn btn-primary owner-button-inline" type="submit">Reset Password</button>
                                                            </form>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php $loginEmailOpen = false; ?>
                            <div class="owner-accordion" data-accordion="users_login_email">
                                <div class="owner-accordion-header">
                                    <button class="owner-accordion-toggle" type="button" aria-expanded="<?php echo $loginEmailOpen ? 'true' : 'false'; ?>" aria-controls="users-email-panel" id="users-email-toggle">
                                        <span class="owner-accordion-indicator" title="<?php echo $loginEmailOpen ? 'Collapse' : 'Expand'; ?>" aria-hidden="true"></span>
                                        <span class="owner-accordion-title">Login Email</span>
                                    </button>
                                </div>
                                <div class="owner-accordion-panel" id="users-email-panel" role="region" aria-labelledby="users-email-toggle"<?php echo $loginEmailOpen ? '' : ' hidden'; ?>>
                                    <div class="owner-section owner-section-compact">
                                        <p class="owner-subtitle owner-subtitle-tight">Update the email used to sign in. This does not change contact form recipients.</p>
                                        <form class="owner-form" method="post" action="">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                            <input type="hidden" name="action" value="change_email">
                                            <input type="hidden" name="active_tab" value="users" data-active-tab>
                                            <p class="owner-help">Current login: <?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES); ?></p>
                                            <label class="owner-field">
                                                <span class="owner-label">Current password</span>
                                                <input class="owner-input" type="password" name="user_email[current]" autocomplete="current-password" placeholder="••••••••">
                                            </label>
                                            <label class="owner-field">
                                                <span class="owner-label">New login email</span>
                                                <input class="owner-input" type="email" name="user_email[new]" placeholder="name@email.com">
                                            </label>
                                            <label class="owner-field">
                                                <span class="owner-label">Confirm login email</span>
                                                <input class="owner-input" type="email" name="user_email[confirm]" placeholder="name@email.com">
                                            </label>
                                            <button class="btn btn-primary owner-button-inline" type="submit">Update Login Email</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <?php $passwordOpen = false; ?>
                            <div class="owner-accordion" data-accordion="users_password">
                                <div class="owner-accordion-header">
                                    <button class="owner-accordion-toggle" type="button" aria-expanded="<?php echo $passwordOpen ? 'true' : 'false'; ?>" aria-controls="users-password-panel" id="users-password-toggle">
                                        <span class="owner-accordion-indicator" title="<?php echo $passwordOpen ? 'Collapse' : 'Expand'; ?>" aria-hidden="true"></span>
                                        <span class="owner-accordion-title">Change Your Password</span>
                                    </button>
                                </div>
                                <div class="owner-accordion-panel" id="users-password-panel" role="region" aria-labelledby="users-password-toggle"<?php echo $passwordOpen ? '' : ' hidden'; ?>>
                                    <div class="owner-section owner-section-compact">
                                        <p class="owner-subtitle owner-subtitle-tight">Update the password for your account.</p>
                                        <form class="owner-form" method="post" action="">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                                            <input type="hidden" name="action" value="change_password">
                                            <input type="hidden" name="active_tab" value="users" data-active-tab>
                                            <label class="owner-field">
                                                <span class="owner-label">Current password</span>
                                                <input class="owner-input" type="password" name="user_password[current]" autocomplete="current-password" placeholder="••••••••">
                                            </label>
                                            <div class="owner-password-panel" data-password-group>
                                                <label class="owner-field">
                                                    <span class="owner-label">New password</span>
                                                    <input class="owner-input" type="password" name="user_password[new]" autocomplete="new-password" placeholder="At least 12 characters" data-password-input="primary">
                                                </label>
                                                <label class="owner-field">
                                                    <span class="owner-label">Confirm new password</span>
                                                    <input class="owner-input" type="password" name="user_password[confirm]" autocomplete="new-password" placeholder="Repeat new password" data-password-input="confirm">
                                                </label>
                                                <div class="owner-password-actions">
                                                    <button class="owner-link-button" type="button" data-password-generate>Generate strong password</button>
                                                    <button class="owner-link-button" type="button" data-password-toggle>Show</button>
                                                    <button class="owner-link-button" type="button" data-password-copy>Copy</button>
                                                </div>
                                                <span class="owner-help owner-password-status" data-password-status aria-live="polite"></span>
                                            </div>
                                            <p class="owner-help">Passwords must be at least 12 characters and include three of: uppercase, lowercase, number, symbol.</p>
                                            <button class="btn btn-primary owner-button-inline" type="submit">Update Password</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </section>
                    </div>
                </section>
            </main>
        </div>
        <script>
            (function () {
                const tabs = Array.from(document.querySelectorAll('[data-tab]'));
                const panels = Array.from(document.querySelectorAll('[data-tab-panel]'));
                const actions = document.querySelector('.owner-actions');
                const lastLogin = document.querySelector('[data-last-login]');
                const lastLoginTime = document.querySelector('[data-last-login-time]');
                const accordions = Array.from(document.querySelectorAll('[data-accordion]'));
                const activeInputs = Array.from(document.querySelectorAll('[data-active-tab]'));
                const tabList = document.querySelector('.owner-tabs');
                let storedAccordionStates = {};

                if (!tabs.length || !panels.length) {
                    return;
                }

                if (lastLoginTime) {
                    const iso = lastLoginTime.getAttribute('data-last-login-time');
                    if (iso) {
                        const date = new Date(iso);
                        if (!Number.isNaN(date.getTime())) {
                            lastLoginTime.textContent = date.toLocaleString(undefined, {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true,
                            });
                        }
                    }
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
                    if (lastLogin) {
                        lastLogin.hidden = tab !== 'general';
                    }

                    if (activeInputs.length) {
                        activeInputs.forEach((input) => {
                            input.value = tab;
                        });
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
                            indicator.setAttribute('title', open ? 'Collapse' : 'Expand');
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

                const fieldRows = Array.from(document.querySelectorAll('[data-field-row]'));
                const syncFieldRow = (row) => {
                    const showToggle = row.querySelector('[data-field-toggle="show"]');
                    const requiredToggle = row.querySelector('[data-field-toggle="required"]');
                    if (!showToggle || !requiredToggle) {
                        return;
                    }
                    const shouldDisable = !showToggle.checked;
                    requiredToggle.disabled = shouldDisable;
                    if (shouldDisable) {
                        requiredToggle.setAttribute('aria-disabled', 'true');
                    } else {
                        requiredToggle.removeAttribute('aria-disabled');
                    }
                    row.classList.toggle('is-required-disabled', shouldDisable);
                };

                fieldRows.forEach((row) => {
                    const showToggle = row.querySelector('[data-field-toggle="show"]');
                    if (!showToggle) {
                        return;
                    }
                    syncFieldRow(row);
                    showToggle.addEventListener('change', () => {
                        syncFieldRow(row);
                    });
                });

                const syncAutoReplyNote = (accordion) => {
                    const note = accordion.querySelector('[data-auto-reply-note]');
                    if (!note) {
                        return;
                    }
                    const autoReplyToggle = accordion.querySelector('[data-auto-reply-toggle]');
                    const emailRow = accordion.querySelector('[data-field-row][data-field-key="email"]');
                    if (!autoReplyToggle || !emailRow) {
                        return;
                    }
                    const showToggle = emailRow.querySelector('[data-field-toggle="show"]');
                    const requiredToggle = emailRow.querySelector('[data-field-toggle="required"]');
                    const shouldShow = autoReplyToggle.checked && (!showToggle || !showToggle.checked || !requiredToggle || !requiredToggle.checked);
                    note.hidden = !shouldShow;
                };

                accordions.forEach((accordion) => {
                    const note = accordion.querySelector('[data-auto-reply-note]');
                    if (!note) {
                        return;
                    }
                    const autoReplyToggle = accordion.querySelector('[data-auto-reply-toggle]');
                    const emailRow = accordion.querySelector('[data-field-row][data-field-key="email"]');
                    if (!autoReplyToggle || !emailRow) {
                        return;
                    }
                    const showToggle = emailRow.querySelector('[data-field-toggle="show"]');
                    const requiredToggle = emailRow.querySelector('[data-field-toggle="required"]');
                    const update = () => {
                        syncAutoReplyNote(accordion);
                    };
                    update();
                    autoReplyToggle.addEventListener('change', update);
                    if (showToggle) {
                        showToggle.addEventListener('change', update);
                    }
                    if (requiredToggle) {
                        requiredToggle.addEventListener('change', update);
                    }
                });

                const confirmButtons = Array.from(document.querySelectorAll('[data-confirm]'));
                confirmButtons.forEach((button) => {
                    button.addEventListener('click', (event) => {
                        const message = button.getAttribute('data-confirm');
                        if (message && !window.confirm(message)) {
                            event.preventDefault();
                        }
                    });
                });

                const passwordGroups = Array.from(document.querySelectorAll('[data-password-group]'));
                const randomInt = (max) => {
                    if (window.crypto && window.crypto.getRandomValues) {
                        const buffer = new Uint32Array(1);
                        window.crypto.getRandomValues(buffer);
                        return buffer[0] % max;
                    }
                    return Math.floor(Math.random() * max);
                };
                const shuffle = (items) => {
                    for (let i = items.length - 1; i > 0; i -= 1) {
                        const j = randomInt(i + 1);
                        [items[i], items[j]] = [items[j], items[i]];
                    }
                    return items;
                };
                const generatePassword = () => {
                    const length = 16;
                    const sets = [
                        'ABCDEFGHJKLMNPQRSTUVWXYZ',
                        'abcdefghijkmnopqrstuvwxyz',
                        '23456789',
                        '!@#$%&*?+-=_',
                    ];
                    const selected = [];
                    while (selected.length < 3) {
                        const index = randomInt(sets.length);
                        if (!selected.includes(index)) {
                            selected.push(index);
                        }
                    }
                    if (selected.length < sets.length && randomInt(2) === 1) {
                        const remaining = sets.map((_, index) => index).filter((index) => !selected.includes(index));
                        if (remaining.length) {
                            selected.push(remaining[randomInt(remaining.length)]);
                        }
                    }
                    const pool = selected.map((index) => sets[index]).join('');
                    const chars = selected.map((index) => {
                        const set = sets[index];
                        return set[randomInt(set.length)];
                    });
                    while (chars.length < length) {
                        chars.push(pool[randomInt(pool.length)]);
                    }
                    return shuffle(chars).join('');
                };

                passwordGroups.forEach((group) => {
                    const inputs = Array.from(group.querySelectorAll('[data-password-input]'));
                    if (!inputs.length) {
                        return;
                    }
                    const primaryInput = group.querySelector('[data-password-input="primary"]') || inputs[0];
                    const confirmInput = group.querySelector('[data-password-input="confirm"]');
                    const toggleButton = group.querySelector('[data-password-toggle]');
                    const copyButton = group.querySelector('[data-password-copy]');
                    const generateButton = group.querySelector('[data-password-generate]');
                    const status = group.querySelector('[data-password-status]');
                    let isVisible = false;

                    const setStatus = (message) => {
                        if (status) {
                            status.textContent = message;
                        }
                    };

                    const setVisibility = (visible) => {
                        inputs.forEach((input) => {
                            input.type = visible ? 'text' : 'password';
                        });
                        if (toggleButton) {
                            toggleButton.textContent = visible ? 'Hide' : 'Show';
                        }
                        isVisible = visible;
                    };

                    const copyPassword = () => {
                        const value = primaryInput ? primaryInput.value : '';
                        if (!value) {
                            setStatus('Nothing to copy yet.');
                            return;
                        }
                        const fallback = () => {
                            if (primaryInput && primaryInput.select) {
                                primaryInput.focus();
                                primaryInput.select();
                                document.execCommand('copy');
                                setStatus('Password copied.');
                            }
                        };
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(value).then(() => {
                                setStatus('Password copied.');
                            }).catch(() => {
                                fallback();
                            });
                        } else {
                            fallback();
                        }
                    };

                    if (toggleButton) {
                        toggleButton.addEventListener('click', () => {
                            setVisibility(!isVisible);
                        });
                    }

                    if (copyButton) {
                        copyButton.addEventListener('click', copyPassword);
                    }

                    if (generateButton) {
                        generateButton.addEventListener('click', () => {
                            const password = generatePassword();
                            if (primaryInput) {
                                primaryInput.value = password;
                            }
                            if (confirmInput) {
                                confirmInput.value = password;
                            }
                            setVisibility(true);
                            setStatus('Generated a strong password.');
                        });
                    }
                });
            })();
        </script>
    </body>
</html>
