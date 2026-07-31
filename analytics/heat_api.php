<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: private, no-store');
define('YAFUSO_ANALYTICS_INTERNAL', true);
$_GET['action'] = 'stats';
require dirname(__DIR__) . '/visitor_tracker.php';
