<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="canonical" href="contact-us.php">
        <link rel="icon" type="image/x-icon" href="https://irp.cdn-website.com/fd5deb14/site_favicon_16_1717534387535.ico">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;subset=latin-ext&amp;display=swap">
        <?php include __DIR__ . "/includes/site-head.php"; ?>
        <link rel="stylesheet" href="contact-us.css">
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
        <?php include __DIR__ . '/includes/site-header.php'; ?>
        <main class="contact-us-main">
            <section class="contact-us-hero">
                <div class="container contact-us-hero-inner">
                    <h1 class="contact-us-title">Contact Us</h1>
                    <p class="contact-us-subtitle">
                        Call, visit, or stop by the shop. We are happy to answer questions and schedule service.
                    </p>
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
                        <a class="btn btn-primary" href="directions.php">Get Directions</a>
                    </div>
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
                        <form class="contact-us-form-grid" method="post" action="" data-form="contact_us" novalidate>
                            <label class="contact-us-field">
                                <span class="contact-us-label">Full name <span class="contact-us-required">*</span></span>
                                <input class="contact-us-input" type="text" name="name" autocomplete="name" data-field="name" required>
                            </label>
                            <label class="contact-us-field">
                                <span class="contact-us-label">Phone number <span class="contact-us-required">*</span></span>
                                <input class="contact-us-input" type="tel" name="phone" autocomplete="tel" data-field="phone" required>
                            </label>
                            <label class="contact-us-field">
                                <span class="contact-us-label">Email address</span>
                                <input class="contact-us-input" type="email" name="email" autocomplete="email" data-field="email">
                            </label>
                            <label class="contact-us-field">
                                <span class="contact-us-label">Vehicle</span>
                                <input class="contact-us-input" type="text" name="vehicle" autocomplete="off" data-field="vehicle" placeholder="Year, make, model">
                            </label>
                            <label class="contact-us-field contact-us-field-full">
                                <span class="contact-us-label">Preferred time</span>
                                <input class="contact-us-input" type="text" name="preferred_time" autocomplete="off" data-field="preferred_time" placeholder="Best time to reach you">
                            </label>
                            <label class="contact-us-field contact-us-field-full">
                                <span class="contact-us-label">Message <span class="contact-us-required">*</span></span>
                                <textarea class="contact-us-input contact-us-textarea" name="message" rows="4" data-field="message" required></textarea>
                            </label>
                            <div class="contact-us-form-actions contact-us-field-full">
                                <button class="btn btn-primary" type="submit">Send message</button>
                                <p class="contact-us-form-note">We respond during business hours.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>
        <?php include __DIR__ . '/includes/site-footer.php'; ?>
        <script src="contact-us.js" defer></script>
    </body>
</html>
