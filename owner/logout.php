<?php

declare(strict_types=1);

require_once __DIR__ . '/lib/auth.php';

owner_send_no_cache_headers();
owner_logout();
owner_set_flash('success', 'Signed out.');
owner_redirect('/owner/login/');
