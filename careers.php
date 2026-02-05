<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="canonical" href="careers.php">
        <link rel="icon" type="image/x-icon" href="https://irp.cdn-website.com/fd5deb14/site_favicon_16_1717534387535.ico">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;subset=latin-ext&amp;display=swap">
        <?php include __DIR__ . "/includes/site-head.php"; ?>
        <link rel="stylesheet" href="careers.css">
        <meta property="og:type" content="website">
        <title>
            Careers - Ticker Automotive
        </title>
        <meta name="keywords" content="auto repair shop hildale, auto repair colorado city, auto repair, auto maintenance, auto service, auto mechanics">
        <meta name="description" content="Apply to join Ticker Automotive. Call with your email address to receive an application, or stop by in person.">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Careers - Ticker Automotive">
        <meta name="twitter:description" content="Apply to join Ticker Automotive. Call with your email address to receive an application, or stop by in person.">
        <meta property="og:description" content="Apply to join Ticker Automotive. Call with your email address to receive an application, or stop by in person.">
        <meta property="og:title" content="Careers - Ticker Automotive">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ME7X83EBJH"></script>
    </head>
    <body class="careers-page">
        <?php include __DIR__ . '/includes/site-header.php'; ?>
        <main class="careers-main">
            <section class="careers-hero">
                <div class="container careers-hero-inner">
                    <h1 class="careers-title">Careers</h1>
                    <p class="careers-subtitle">
                        Interested in joining Ticker Automotive? Call with your email address to receive an application,
                        or stop by in person.
                    </p>
                </div>
            </section>
            <section class="careers-info">
                <div class="container careers-info-inner">
                    <div class="careers-card">
                        <h2>Apply in Person</h2>
                        <p>
                            Stop by the shop and let us know you are interested in a position. We are happy to answer
                            questions and point you in the right direction.
                        </p>
                        <p data-business-hours>MON-FRI 9:00 AM - 5:00 PM</p>
                        <a class="btn btn-primary" href="directions.php">Get Directions</a>
                    </div>
                    <div class="careers-card">
                        <h2>Apply by Phone</h2>
                        <p>
                            Call us and share your email address. We will send an application and let you know
                            about current openings.
                        </p>
                        <a class="btn btn-primary" href="<?php echo htmlspecialchars($sitePrimaryPhoneHref, ENT_QUOTES); ?>">Call <?php echo htmlspecialchars($sitePrimaryPhone, ENT_QUOTES); ?></a>
                    </div>
                    <div class="careers-card">
                        <h2>Inquire by Email</h2>
                        <p>
                            Send a quick note with your name, contact details, and the role you are interested in.
                        </p>
                        <a class="btn btn-primary" href="<?php echo htmlspecialchars($sitePrimaryEmailHref, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sitePrimaryEmail, ENT_QUOTES); ?></a>
                    </div>
                </div>
            </section>
        </main>
        <?php include __DIR__ . '/includes/site-footer.php'; ?>
        <script src="careers.js" defer></script>
    </body>
</html>
