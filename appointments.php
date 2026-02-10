<?php
$serviceOptions = [];
$servicesDir = __DIR__ . '/services';
if (is_dir($servicesDir)) {
    $files = glob($servicesDir . '/*.html') ?: [];
    $labels = [];
    foreach ($files as $file) {
        $label = basename($file, '.html');
        $label = str_replace('_', ' ', $label);
        $label = preg_replace('/\s+/', ' ', $label);
        $label = trim((string) $label);
        if ($label !== '') {
            $labels[] = $label;
        }
    }
    if (!empty($labels)) {
        natcasesort($labels);
        $serviceOptions = array_values(array_unique($labels));
    }
}

$appointmentsFields = [
    'name' => ['enabled' => true, 'required' => true],
    'phone' => ['enabled' => true, 'required' => true],
    'email' => ['enabled' => true, 'required' => false],
    'service' => ['enabled' => true, 'required' => false],
    'year' => ['enabled' => true, 'required' => false],
    'make' => ['enabled' => true, 'required' => false],
    'model' => ['enabled' => true, 'required' => false],
    'license_plate' => ['enabled' => true, 'required' => false],
    'vin' => ['enabled' => true, 'required' => false],
    'preferred_time' => ['enabled' => true, 'required' => false],
    'message' => ['enabled' => true, 'required' => false],
];

$ownerConfig = __DIR__ . '/owner/config.php';
if (is_file($ownerConfig)) {
    require_once __DIR__ . '/owner/lib/settings.php';
    $ownerSettings = owner_load_settings();
    $storedFields = $ownerSettings['contact_forms']['appointments']['fields'] ?? [];
    if (is_array($storedFields)) {
        foreach ($appointmentsFields as $fieldKey => $fieldState) {
            $override = $storedFields[$fieldKey] ?? null;
            if (!is_array($override)) {
                continue;
            }
            $appointmentsFields[$fieldKey]['enabled'] = !empty($override['enabled']);
            $appointmentsFields[$fieldKey]['required'] = !empty($override['required']);
        }
    }
}
?>
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
                            <?php if (!empty($appointmentsFields['name']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">Full name<?php if (!empty($appointmentsFields['name']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="text" name="name" autocomplete="name" data-field="name"<?php echo !empty($appointmentsFields['name']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['phone']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">Phone number<?php if (!empty($appointmentsFields['phone']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="tel" name="phone" autocomplete="tel" data-field="phone"<?php echo !empty($appointmentsFields['phone']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['email']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">Email address<?php if (!empty($appointmentsFields['email']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="email" name="email" autocomplete="email" data-field="email"<?php echo !empty($appointmentsFields['email']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['service']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">Service requested<?php if (!empty($appointmentsFields['service']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <select class="appointments-input" name="service" data-field="service"<?php echo !empty($appointmentsFields['service']['required']) ? ' required' : ''; ?>>
                                        <option value="">Select a service</option>
                                        <?php foreach ($serviceOptions as $option): ?>
                                            <option value="<?php echo htmlspecialchars($option, ENT_QUOTES); ?>"><?php echo htmlspecialchars($option, ENT_QUOTES); ?></option>
                                        <?php endforeach; ?>
                                        <option value="Other">Other</option>
                                    </select>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['year']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">Year<?php if (!empty($appointmentsFields['year']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="text" name="year" autocomplete="off" data-field="year" placeholder="2020"<?php echo !empty($appointmentsFields['year']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['make']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">Make<?php if (!empty($appointmentsFields['make']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="text" name="make" autocomplete="off" data-field="make" placeholder="Toyota"<?php echo !empty($appointmentsFields['make']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['model']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">Model<?php if (!empty($appointmentsFields['model']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="text" name="model" autocomplete="off" data-field="model" placeholder="Camry"<?php echo !empty($appointmentsFields['model']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['license_plate']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">License plate<?php if (!empty($appointmentsFields['license_plate']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="text" name="license_plate" autocomplete="off" data-field="license_plate" placeholder="ABC123"<?php echo !empty($appointmentsFields['license_plate']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['vin']['enabled'])): ?>
                                <label class="appointments-field">
                                    <span class="appointments-label">VIN<?php if (!empty($appointmentsFields['vin']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="text" name="vin" autocomplete="off" data-field="vin" placeholder="17-digit VIN"<?php echo !empty($appointmentsFields['vin']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['preferred_time']['enabled'])): ?>
                                <label class="appointments-field appointments-field-full">
                                    <span class="appointments-label">Preferred time<?php if (!empty($appointmentsFields['preferred_time']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <input class="appointments-input" type="text" name="preferred_time" autocomplete="off" data-field="preferred_time" placeholder="Weekday mornings, next Tuesday, etc."<?php echo !empty($appointmentsFields['preferred_time']['required']) ? ' required' : ''; ?>>
                                </label>
                            <?php endif; ?>
                            <?php if (!empty($appointmentsFields['message']['enabled'])): ?>
                                <label class="appointments-field appointments-field-full">
                                    <span class="appointments-label">Message<?php if (!empty($appointmentsFields['message']['required'])): ?> <span class="appointments-required">*</span><?php endif; ?></span>
                                    <textarea class="appointments-input appointments-textarea" name="message" rows="4" data-field="message"<?php echo !empty($appointmentsFields['message']['required']) ? ' required' : ''; ?>></textarea>
                                </label>
                            <?php endif; ?>
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
