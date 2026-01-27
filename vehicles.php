<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="canonical" href="vehicles.php">
        <link rel="icon" type="image/x-icon" href="https://irp.cdn-website.com/fd5deb14/site_favicon_16_1717534387535.ico">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;subset=latin-ext&amp;display=swap">
        <?php include __DIR__ . "/includes/site-head.php"; ?>
        <link rel="stylesheet" href="vehicles.css">
        <meta property="og:type" content="website">
        <title>
            Vehicles - Ticker Automotive
        </title>
        <meta name="keywords" content="auto repair shop hildale, auto repair colorado city, auto repair, auto maintenance, auto service, auto mechanics">
        <meta name="description" content="Ticker Automotive is a family-owned and operated auto repair shop in Hildale, UT with over 25 years of experience serving Hildale, Colorado City, and the area.">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Vehicles - Ticker Automotive">
        <meta name="twitter:description" content="Ticker Automotive is a family-owned and operated auto repair shop in Hildale, UT with over 25 years of experience serving Hildale, Colorado City, and the area.">
        <meta property="og:description" content="Ticker Automotive is a family-owned and operated auto repair shop in Hildale, UT with over 25 years of experience serving Hildale, Colorado City, and the area.">
        <meta property="og:title" content="Vehicles - Ticker Automotive">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ME7X83EBJH"></script>
    </head>
    <?php
        include __DIR__ . '/includes/content-list.php';
        $vehicleItems = load_content_items(__DIR__ . '/vehicles');
        $vehicleItems = array_map(function ($item) {
            $imagePath = __DIR__ . '/vehicles/' . $item['basename'] . '.webp';
            $item['image'] = file_exists($imagePath) ? 'vehicles/' . $item['basename'] . '.webp' : '';
            return $item;
        }, $vehicleItems);
    ?>
    <body class="vehicles-page">
        <?php include __DIR__ . '/includes/site-header.php'; ?>
        <main class="vehicles-main">
            <section class="vehicles-hero">
                <div class="container vehicles-hero-inner">
                    <h1 class="vehicles-title">Vehicles</h1>
                    <p class="vehicles-subtitle">
                        Explore the makes and models we service.
                    </p>
                </div>
            </section>
            <section class="vehicles-directory">
                <div class="container">
                    <div class="vehicles-directory-inner">
                        <nav class="vehicles-links" aria-label="Vehicle list">
                            <?php if ($vehicleItems) { ?>
                                <ul class="vehicles-link-list" role="list">
                                    <?php foreach ($vehicleItems as $index => $vehicle) { ?>
                                        <li>
                                            <button class="vehicle-link" type="button" data-vehicle-index="<?php echo $index; ?>" aria-pressed="false">
                                                <?php echo htmlspecialchars($vehicle['title']); ?>
                                            </button>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } else { ?>
                                <p class="vehicles-empty">Content is coming soon. Please check back.</p>
                            <?php } ?>
                        </nav>
                        <?php if ($vehicleItems) { ?>
                            <div class="vehicle-detail-card" aria-live="polite" hidden>
                                <div class="vehicle-detail-media" hidden>
                                    <img src="" alt="" loading="lazy">
                                </div>
                                <div class="vehicle-detail-content">
                                    <h3 class="vehicle-detail-title"></h3>
                                    <div class="vehicle-detail-copy"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </main>
        <?php include __DIR__ . '/includes/site-footer.php'; ?>
        <script>
            window.vehicleItems = <?php echo json_encode($vehicleItems, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
        </script>
        <script src="vehicles.js" defer></script>
    </body>
</html>
