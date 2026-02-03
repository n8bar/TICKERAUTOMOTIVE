<?php

declare(strict_types=1);

require_once __DIR__ . '/lib/auth.php';

owner_send_no_cache_headers();
owner_start_session();

if (!empty($_SESSION['auth'])) {
    owner_redirect('/owner/settings/');
}

owner_redirect('/owner/login/');
