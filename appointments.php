<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="canonical" href="appointments.php">
        <link rel="icon" type="image/x-icon" href="https://irp.cdn-website.com/fd5deb14/site_favicon_16_1717534387535.ico">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;subset=latin-ext&amp;display=swap">
        <?php include __DIR__ . "/includes/site-head.php"; ?>
        <link rel="stylesheet" href="appointments.css">
        <meta property="og:type" content="website">
        <title>
            Schedule an Appointment - Ticker Automotive
        </title>
        <meta name="keywords" content="auto repair appointments, schedule auto service, hildale auto repair">
        <meta name="description" content="Schedule an appointment with Ticker Automotive. Call or visit to book your service while we finalize online scheduling.">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Schedule an Appointment - Ticker Automotive">
        <meta name="twitter:description" content="Schedule an appointment with Ticker Automotive. Call or visit to book your service while we finalize online scheduling.">
        <meta property="og:description" content="Schedule an appointment with Ticker Automotive. Call or visit to book your service while we finalize online scheduling.">
        <meta property="og:title" content="Schedule an Appointment - Ticker Automotive">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ME7X83EBJH"></script>
    </head>
    <body class="appointments-page">
        <?php include __DIR__ . '/includes/site-header.php'; ?>
        <main class="appointments-main">
            <section class="appointments-hero">
                <div class="container appointments-hero-inner">
                    <h1 class="appointments-title">Schedule an Appointment</h1>
                    <p class="appointments-subtitle">
                        Online scheduling is coming soon. In the meantime, send us a request and we will confirm your appointment.
                    </p>
                </div>
            </section>
            <section class="appointments-form">
                <div class="container appointments-form-inner">
                    <div class="appointments-form-card">
                        <div class="appointments-form-header">
                            <h2>Request an Appointment</h2>
                            <p>
                                Share a few details and we will follow up during business hours to lock in the best time.
                            </p>
                        </div>
                        <form class="appointments-form-grid" method="post" action="" data-form="appointments" novalidate>
                            <label class="appointments-field">
                                <span class="appointments-label">Full name <span class="appointments-required">*</span></span>
                                <input class="appointments-input" type="text" name="name" autocomplete="name" data-field="name" required>
                            </label>
                            <label class="appointments-field">
                                <span class="appointments-label">Phone number <span class="appointments-required">*</span></span>
                                <input class="appointments-input" type="tel" name="phone" autocomplete="tel" data-field="phone" required>
                            </label>
                            <label class="appointments-field">
                                <span class="appointments-label">Email address</span>
                                <input class="appointments-input" type="email" name="email" autocomplete="email" data-field="email">
                            </label>
                            <label class="appointments-field">
                                <span class="appointments-label">Vehicle</span>
                                <input class="appointments-input" type="text" name="vehicle" autocomplete="off" data-field="vehicle" placeholder="Year, make, model">
                            </label>
                            <label class="appointments-field appointments-field-full">
                                <span class="appointments-label">Preferred time</span>
                                <input class="appointments-input" type="text" name="preferred_time" autocomplete="off" data-field="preferred_time" placeholder="Weekday mornings, next Tuesday, etc.">
                            </label>
                            <label class="appointments-field appointments-field-full">
                                <span class="appointments-label">Message <span class="appointments-required">*</span></span>
                                <textarea class="appointments-input appointments-textarea" name="message" rows="4" data-field="message" required></textarea>
                            </label>
                            <div class="appointments-form-actions appointments-field-full">
                                <button class="btn btn-primary" type="submit">Send request</button>
                                <p class="appointments-form-note">We respond during business hours.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <section class="appointments-info">
                <div class="container appointments-info-inner">
                    <div class="appointments-card">
                        <h2>Call to Schedule</h2>
                        <p>
                            We will confirm availability and the best time for your vehicle. If you need towing or
                            have questions, let us know when you call.
                        </p>
                        <a class="btn btn-primary" href="<?php echo htmlspecialchars($sitePrimaryPhoneHref, ENT_QUOTES); ?>">Call <?php echo htmlspecialchars($sitePrimaryPhone, ENT_QUOTES); ?></a>
                    </div>
                    <div class="appointments-card">
                        <h2>Hours &amp; Location</h2>
                        <p data-business-hours><?php echo htmlspecialchars($siteBusinessHours, ENT_QUOTES); ?></p>
                        <p data-nap-lines>
                            Ticker Automotive<br>
                            <?php echo htmlspecialchars($siteAddressInline, ENT_QUOTES); ?><br>
                            <?php echo htmlspecialchars($sitePrimaryPhone, ENT_QUOTES); ?>
                        </p>
                        <a class="btn btn-primary" href="directions.php">Get Directions</a>
                    </div>
                    <div class="appointments-card">
                        <h2>Email Us</h2>
                        <p>
                            Send your request anytime and we will follow up during business hours.
                        </p>
                        <a class="btn btn-primary" href="<?php echo htmlspecialchars($sitePrimaryEmailHref, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sitePrimaryEmail, ENT_QUOTES); ?></a>
                    </div>
                </div>
            </section>
        </main>
        <?php include __DIR__ . '/includes/site-footer.php'; ?>
        <script src="appointments.js" defer></script>
    </body>
</html>
