<?php
$siteRoot = dirname(__DIR__, 3);

require_once $siteRoot . '/owner/lib/auth.php';

owner_send_no_cache_headers();
owner_require_login();

require_once $siteRoot . '/includes/form-handler.php';

$contactDefaults = [
    'name' => ['enabled' => true, 'required' => true],
    'phone' => ['enabled' => true, 'required' => true],
    'email' => ['enabled' => true, 'required' => false],
    'message' => ['enabled' => true, 'required' => false],
];

$contactConfig = site_form_get_contact_form('contact_us');
$contactFields = site_form_merge_fields($contactDefaults, $contactConfig['fields'] ?? []);
$contactState = site_form_handle_submission('contact_us', 'Contact Message', $contactFields, $contactConfig);
$contactEnabled = !empty($contactConfig['enabled']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <base href="/">
        <link rel="canonical" href="/contact-us.php">
        <meta name="robots" content="noindex, nofollow">
        <link rel="icon" type="image/x-icon" href="https://irp.cdn-website.com/fd5deb14/site_favicon_16_1717534387535.ico">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;subset=latin-ext&amp;display=swap">
        <?php require_once $siteRoot . "/includes/site-settings.php"; ?>
        <link rel="stylesheet" href="/site-shell.css">
        <script src="/site-shell.js" defer></script>
        <link rel="stylesheet" href="/contact-us.css">
        <meta property="og:type" content="website">
        <title>
            Contact Us - Ticker Automotive
        </title>
        <meta name="keywords" content="contact ticker automotive, hildale auto repair contact, call ticker automotive">
        <meta name="description" content="Contact Ticker Automotive in Hildale, UT. Call or visit the shop for questions, repairs, or scheduling.">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Contact Us - Ticker Automotive">
        <meta name="twitter:description" content="Contact Ticker Automotive in Hildale, UT. Call or visit the shop for questions, repairs, or scheduling.">
        <meta property="og:description" content="Contact Ticker Automotive in Hildale, UT. Call or visit the shop for questions, repairs, or scheduling.">
        <meta property="og:title" content="Contact Us - Ticker Automotive">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ME7X83EBJH"></script>
    </head>
    <body class="contact-us-page">
        <?php include $siteRoot . '/includes/site-header.php'; ?>
        <main class="contact-us-main">
            <section class="contact-us-hero">
                <div class="container contact-us-hero-inner">
                    <h1 class="contact-us-title">Contact Us</h1>
                    <p class="contact-us-subtitle">
                        Call, visit, or stop by the shop. We are happy to answer questions and schedule service.
                    </p>
                </div>
            </section>
            <section class="contact-us-form">
                <div class="container contact-us-form-inner">
                    <div class="contact-us-form-card">
                        <div class="contact-us-form-header">
                            <h2>Send a Message</h2>
                            <p>
                                Let us know how we can help and we will respond as soon as we can.
                            </p>
                        </div>
                        <?php if ($contactState['success']): ?>
                            <div class="contact-us-alert contact-us-alert-success" role="status">
                                <?php echo htmlspecialchars($contactState['message'], ENT_QUOTES); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($contactState['errors'])): ?>
                            <div class="contact-us-alert contact-us-alert-error" role="alert">
                                <p>Please review the following:</p>
                                <ul>
                                    <?php foreach ($contactState['errors'] as $error): ?>
                                        <li><?php echo htmlspecialchars($error, ENT_QUOTES); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (!$contactEnabled): ?>
                            <div class="contact-us-alert contact-us-alert-error" role="status">
                                Online messages are currently paused. Please call us instead.
                            </div>
                        <?php endif; ?>
                        <?php if ($contactEnabled): ?>
                            <form class="contact-us-form-grid" method="post" action="" data-form="contact_us" novalidate>
                                <input type="hidden" name="form_key" value="contact_us">
                                <input type="hidden" name="form_started" value="<?php echo time(); ?>">
                                <div class="contact-us-honeypot" aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">
                                    <input type="text" name="website" tabindex="-1" autocomplete="off" aria-label="Leave this field blank">
                                </div>
                                <?php if (!empty($contactFields['name']['enabled'])): ?>
                                <label class="contact-us-field">
                                    <span class="contact-us-label">Full name<?php if (!empty($contactFields['name']['required'])): ?> <span class="contact-us-required">*</span><?php endif; ?></span>
                                    <input class="contact-us-input" type="text" name="name" autocomplete="name" data-field="name" value="<?php echo htmlspecialchars($contactState['values']['name'] ?? '', ENT_QUOTES); ?>"<?php echo !empty($contactFields['name']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($contactFields['phone']['enabled'])): ?>
                                <label class="contact-us-field">
                                    <span class="contact-us-label">Phone number<?php if (!empty($contactFields['phone']['required'])): ?> <span class="contact-us-required">*</span><?php endif; ?></span>
                                    <input class="contact-us-input" type="tel" name="phone" autocomplete="tel" data-field="phone" value="<?php echo htmlspecialchars($contactState['values']['phone'] ?? '', ENT_QUOTES); ?>"<?php echo !empty($contactFields['phone']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($contactFields['email']['enabled'])): ?>
                                <label class="contact-us-field">
                                    <span class="contact-us-label">Email address<?php if (!empty($contactFields['email']['required'])): ?> <span class="contact-us-required">*</span><?php endif; ?></span>
                                    <input class="contact-us-input" type="email" name="email" autocomplete="email" data-field="email" value="<?php echo htmlspecialchars($contactState['values']['email'] ?? '', ENT_QUOTES); ?>"<?php echo !empty($contactFields['email']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($contactFields['message']['enabled'])): ?>
                                <label class="contact-us-field contact-us-field-full">
                                    <span class="contact-us-label">Message<?php if (!empty($contactFields['message']['required'])): ?> <span class="contact-us-required">*</span><?php endif; ?></span>
                                    <textarea class="contact-us-input contact-us-textarea" name="message" rows="4" data-field="message"<?php echo !empty($contactFields['message']['required']) ? ' required' : ''; ?>><?php echo htmlspecialchars($contactState['values']['message'] ?? '', ENT_QUOTES); ?></textarea>
                                </label>
                            <?php endif; ?>
                            <div class="contact-us-form-actions contact-us-field-full">
                                <button class="btn btn-primary" type="submit">Send message</button>
                                <p class="contact-us-form-note">We respond during business hours.</p>
                            </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <section class="contact-us-info">
                <div class="container contact-us-info-inner">
                    <div class="contact-us-card">
                        <h2>Call Us</h2>
                        <p>
                            Talk with our team about repairs, tires, towing, or scheduling. We will help you find
                            the right next step.
                        </p>
                        <a class="btn btn-primary" href="<?php echo htmlspecialchars($sitePrimaryPhoneHref, ENT_QUOTES); ?>">Call <?php echo htmlspecialchars($sitePrimaryPhone, ENT_QUOTES); ?></a>
                    </div>
                    <div class="contact-us-card">
                        <h2>Visit the Shop</h2>
                        <p data-business-hours><?php echo htmlspecialchars($siteBusinessHours, ENT_QUOTES); ?></p>
                        <p data-nap-lines>
                            Ticker Automotive<br>
                            <?php echo htmlspecialchars($siteAddressInline, ENT_QUOTES); ?><br>
                            <?php echo htmlspecialchars($sitePrimaryPhone, ENT_QUOTES); ?>
                        </p>
                        <p>
                            After-hours: <a href="<?php echo htmlspecialchars($siteAfterHoursPhoneHref, ENT_QUOTES); ?>"><?php echo htmlspecialchars($siteAfterHoursPhone, ENT_QUOTES); ?></a> (call or text, voicemail if no answer)
                        </p>
                        <p>
                            Email: <a href="<?php echo htmlspecialchars($sitePrimaryEmailHref, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sitePrimaryEmail, ENT_QUOTES); ?></a>
                        </p>
                        <a class="btn btn-primary" href="/directions.php">Get Directions</a>
                    </div>
                </div>
            </section>
        </main>
        <?php include $siteRoot . '/includes/site-footer.php'; ?>
        <script src="/contact-us.js" defer></script>
        <script>
            document.addEventListener('click', (event) => {
                const link = event.target.closest('a');
                if (!link) {
                    return;
                }
                const href = link.getAttribute('href') || '';
                if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                    return;
                }
                event.preventDefault();
            });
        </script>
    </body>
</html>
