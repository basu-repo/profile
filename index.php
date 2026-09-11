<?php
declare(strict_types=1);

/**
 * Front controller. Every request the web server cannot satisfy with a real
 * file arrives here, is matched against app/routes.php, and is handed to a
 * controller in app/Controllers.
 */

require __DIR__ . '/app/Core/bootstrap.php';

route_dispatch(require __DIR__ . '/app/routes.php');
