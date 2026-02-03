<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/auth.php';

owner_send_no_cache_headers();
owner_start_session();

if (!empty($_SESSION['auth'])) {
    owner_redirect('/owner/settings/');
}

$errors = [];
$notices = [];
$flash = owner_get_flash();
$lockoutRemaining = 0;

if (owner_is_locked_out()) {
    $lockoutRemaining = max(0, (int) ($_SESSION['lockout_until'] ?? 0) - time());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!owner_verify_csrf(is_string($csrfToken) ? $csrfToken : null)) {
        $errors[] = 'Security check failed. Please try again.';
    } elseif ($lockoutRemaining > 0) {
        $errors[] = 'Too many login attempts. Try again in a few minutes.';
    } else {
        $action = $_POST['action'] ?? '';
        if ($action === 'login') {
            $email = strtolower(trim((string) ($_POST['email'] ?? '')));
            $password = (string) ($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $errors[] = 'Enter your email and password.';
            } else {
                $account = owner_verify_credentials($email, $password);
                if (!$account) {
                    owner_note_failed_login();
                    $errors[] = 'Email or password is incorrect.';
                } elseif (owner_requires_otp()) {
                    owner_set_pending_account($account);
                    if (owner_send_otp($account)) {
                        $notices[] = 'A one-time code was sent to your email.';
                    } else {
                        owner_clear_pending_account();
                        $errors[] = 'Unable to send the login code. Please confirm SMTP settings.';
                    }
                } else {
                    owner_login_user($account);
                    owner_redirect('/owner/settings/');
                }
            }
        }

        if ($action === 'verify_otp') {
            $code = trim((string) ($_POST['otp_code'] ?? ''));
            $pending = owner_pending_account();

            if (!$pending) {
                $errors[] = 'Your login session expired. Please sign in again.';
            } elseif ($code === '') {
                $errors[] = 'Enter the login code sent to your email.';
            } elseif (!owner_verify_otp($code)) {
                $errors[] = 'The code is invalid or expired.';
            } else {
                owner_login_user($pending);
                owner_redirect('/owner/settings/');
            }
        }

        if ($action === 'resend_otp') {
            $pending = owner_pending_account();
            if (!$pending) {
                $errors[] = 'Your login session expired. Please sign in again.';
            } elseif (owner_send_otp($pending)) {
                $notices[] = 'A new one-time code was sent.';
            } else {
                $errors[] = 'Unable to send the login code. Please confirm SMTP settings.';
            }
        }
    }
}

$pending = owner_pending_account();
$showOtp = $pending && owner_requires_otp();
$csrfToken = owner_csrf_token();

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
        <title>Owner Login - Ticker Automotive</title>
    </head>
    <body class="owner-page">
        <div class="owner-shell">
            <header class="owner-header">
                <div class="container owner-header-inner">
                    <div class="owner-brand">
                        <span class="owner-brand-title">Ticker Automotive</span>
                        <span class="owner-brand-subtitle">Owner Portal</span>
                    </div>
                </div>
            </header>
            <main class="owner-main">
                <section class="owner-card">
                    <h1 class="owner-title">Owner Login</h1>
                    <p class="owner-subtitle">Sign in to manage website settings and forms.</p>

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

                    <?php if ($lockoutRemaining > 0): ?>
                        <div class="owner-alert owner-alert-error">
                            Too many login attempts. Try again in about <?php echo ceil($lockoutRemaining / 60); ?> minutes.
                        </div>
                    <?php endif; ?>

                    <?php if ($showOtp): ?>
                        <form class="owner-form" method="post" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                            <input type="hidden" name="action" value="verify_otp">
                            <label class="owner-field">
                                <span class="owner-label">One-time code</span>
                                <input class="owner-input" type="text" name="otp_code" autocomplete="one-time-code" inputmode="numeric" placeholder="Enter 6-digit code">
                            </label>
                            <button class="btn btn-primary owner-button" type="submit">Verify &amp; Continue</button>
                        </form>
                        <form class="owner-form owner-form-inline" method="post" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                            <input type="hidden" name="action" value="resend_otp">
                            <button class="owner-link-button" type="submit">Resend code</button>
                        </form>
                    <?php else: ?>
                        <form class="owner-form" method="post" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
                            <input type="hidden" name="action" value="login">
                            <label class="owner-field">
                                <span class="owner-label">Email</span>
                                <input class="owner-input" type="email" name="email" autocomplete="username" placeholder="name@email.com">
                            </label>
                            <label class="owner-field">
                                <span class="owner-label">Password</span>
                                <input class="owner-input" type="password" name="password" autocomplete="current-password" placeholder="••••••••">
                            </label>
                            <button class="btn btn-primary owner-button" type="submit">Sign In</button>
                        </form>
                    <?php endif; ?>

                    <p class="owner-note">
                        Bookmark this link. There is no public navigation to the owner portal.
                    </p>
                </section>
            </main>
        </div>
    </body>
</html>
