<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="canonical" href="services.php">
        <link rel="icon" type="image/x-icon" href="https://irp.cdn-website.com/fd5deb14/site_favicon_16_1717534387535.ico">
        <link rel="preconnect" href="https://lirp.cdn-website.com/">
        <link rel="stylesheet" href="https://irp.cdn-website.com/fonts/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;family=Inter:ital,wght@0,100..900;1,100..900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;family=Work+Sans:ital,wght@0,100..900;1,100..900&amp;family=Alfa+Slab+One:ital,wght@0,400&amp;subset=latin-ext&amp;display=swap">
        <?php include __DIR__ . "/includes/site-head.php"; ?>
        <link rel="stylesheet" href="services.css">
        <meta property="og:type" content="website">
        <title>
            Services - Ticker Automotive
        </title>
        <meta name="keywords" content="auto repair shop hildale, auto repair colorado city, auto repair, auto maintenance, auto service, auto mechanics">
        <meta name="description" content="Ticker Automotive is a family-owned and operated auto repair shop in Hildale, UT with over 25 years of experience serving Hildale, Colorado City, and the area.">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Services - Ticker Automotive">
        <meta name="twitter:description" content="Ticker Automotive is a family-owned and operated auto repair shop in Hildale, UT with over 25 years of experience serving Hildale, Colorado City, and the area.">
        <meta property="og:description" content="Ticker Automotive is a family-owned and operated auto repair shop in Hildale, UT with over 25 years of experience serving Hildale, Colorado City, and the area.">
        <meta property="og:title" content="Services - Ticker Automotive">
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ME7X83EBJH"></script>
    </head>
    <?php
        include __DIR__ . '/includes/content-list.php';
        $serviceItems = load_content_items(__DIR__ . '/services');
        $serviceItems = array_map(function ($item) {
            $extensions = ['webp', 'png', 'jpg', 'jpeg'];
            $image = '';
            foreach ($extensions as $ext) {
                $path = __DIR__ . '/services/' . $item['basename'] . '.' . $ext;
                if (file_exists($path)) {
                    $image = 'services/' . $item['basename'] . '.' . $ext;
                    break;
                }
            }
            $item['image'] = $image;
            return $item;
        }, $serviceItems);
    ?>
    <body class="services-page">
        <?php include __DIR__ . '/includes/site-header.php'; ?>
        <main class="services-main">
            <section class="services-hero">
                <div class="container services-hero-inner">
                    <h1 class="services-title">Services</h1>
                    <p class="services-subtitle">
                        Explore our service offerings and learn what we can help with.
                    </p>
                </div>
            </section>
            <section class="services-directory">
                <div class="container">
                    <div class="services-directory-inner">
                        <nav class="services-links" aria-label="Service list">
                            <?php if ($serviceItems) { ?>
                                <ul class="services-link-list" role="list">
                                    <?php foreach ($serviceItems as $index => $service) { ?>
                                        <li>
                                            <button class="service-link" type="button" data-service-index="<?php echo $index; ?>" aria-pressed="false">
                                                <?php echo htmlspecialchars($service['title']); ?>
                                            </button>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } else { ?>
                                <p class="services-empty">Content is coming soon. Please check back.</p>
                            <?php } ?>
                        </nav>
                        <?php if ($serviceItems) { ?>
                            <div class="service-detail-card" aria-live="polite" hidden>
                                <div class="service-detail-media" hidden>
                                    <img src="" alt="" loading="lazy">
                                </div>
                                <div class="service-detail-content">
                                    <h2 class="service-detail-title"></h2>
                                    <div class="service-detail-copy"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </main>
        <?php include __DIR__ . '/includes/site-footer.php'; ?>
        <script>
            window.serviceItems = <?php echo json_encode($serviceItems, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
        </script>
        <script src="services.js" defer></script>
    </body>
</html>
