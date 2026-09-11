<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_auth.php';

admin_logout();
admin_set_flash('success', 'You have been signed out.');
redirect('login.php');
