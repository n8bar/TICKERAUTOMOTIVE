<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="canonical" href="directions.php">
        <link rel="icon" type="image/x-icon" href="https://irp.cdn-website.com/fd5deb14/site_favicon_16_1717534387535.ico">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;subset=latin-ext&amp;display=swap">
        <?php include __DIR__ . "/includes/site-head.php"; ?>
        <link rel="stylesheet" href="directions.css">
        <meta property="og:type" content="website">
        <title>
            Directions - Ticker Automotive
        </title>
        <meta name="keywords" content="auto repair shop hildale, auto repair colorado city, auto repair, auto maintenance, auto service, auto mechanics">
        <meta name="description" content="Get directions to Ticker Automotive. Choose Google Maps, Apple Maps, or OpenStreetMap.">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Directions - Ticker Automotive">
        <meta name="twitter:description" content="Get directions to Ticker Automotive. Choose Google Maps, Apple Maps, or OpenStreetMap.">
        <meta property="og:description" content="Get directions to Ticker Automotive. Choose Google Maps, Apple Maps, or OpenStreetMap.">
        <meta property="og:title" content="Directions - Ticker Automotive">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ME7X83EBJH"></script>
    </head>
    <body class="directions-page">
        <?php include __DIR__ . '/includes/site-header.php'; ?>
        <main class="directions-main">
            <section class="directions-hero">
                <div class="container directions-hero-inner">
                    <h1 class="directions-title">Directions</h1>
                    <p class="directions-subtitle">
                        Choose your preferred map to get directions to Ticker Automotive.
                    </p>
                </div>
            </section>
            <section class="directions-info">
                <div class="container directions-info-inner">
                    <div class="directions-card">
                        <h2>Ticker Automotive</h2>
                        <div class="directions-hero-image">
                            <img src="lirp.cdn-website.com/fd5deb14/dms3rep/multi/opt/slider_01-2304w.png" alt="Ticker Automotive shop exterior" loading="lazy">
                        </div>
                        <div class="directions-map-image">
                            <img src="images/Map.png" alt="Ticker Automotive shop exterior" loading="lazy">
                        </div>
                    </div>
                    <div class="directions-card">
                        <p class="directions-address">
                            <?php if ($siteAddressLine1 !== ''): ?>
                                <?php echo htmlspecialchars($siteAddressLine1, ENT_QUOTES); ?><br>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($siteAddressLine2, ENT_QUOTES); ?>
                        </p>
                        <p class="directions-hours" data-business-hours>
                            MON-FRI 9:00 AM - 5:00 PM
                        </p>
                        <h2>Choose Your Map</h2>
                        <div class="directions-buttons">
                            <a class="btn btn-primary" href="https://www.google.com/maps/dir/?api=1&amp;destination=<?php echo htmlspecialchars($siteAddressQuery, ENT_QUOTES); ?>" target="_blank" rel="noopener">
                                Google Maps
                            </a>
                            <a class="btn btn-primary" href="https://maps.apple.com/?daddr=<?php echo htmlspecialchars($siteAddressQuery, ENT_QUOTES); ?>" target="_blank" rel="noopener">
                                Apple Maps
                            </a>
                            <a class="btn btn-primary" href="https://share.here.com/g/37.001280710053706,-112.99949573063222,Your%20location/37.001280710053706,-112.99949573063222,<?php echo htmlspecialchars($siteAddressHereQuery, ENT_QUOTES); ?>?a=&amp;m=d&amp;z=20&amp;t=satellite" target="_blank" rel="noopener">
                                HERE WeGo
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include __DIR__ . '/includes/site-footer.php'; ?>
        <script src="directions.js" defer></script>
    </body>
</html>
