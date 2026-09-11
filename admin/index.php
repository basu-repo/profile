<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_auth.php';

if (admin_count_users() === 0) {
    redirect('setup.php');
}

if (admin_is_logged_in()) {
    redirect('dashboard.php');
}

redirect('login.php');
