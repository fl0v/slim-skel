<?php

// phpcs:disable

include_once 'env.php';

\defined('APP_ENV') or \define('APP_ENV', 'production');
\defined('APP_DEBUG') or \define('APP_DEBUG', false);
\defined('APP_ROOT') or \define('APP_ROOT', dirname(__DIR__));
\defined('APP_HOST') or \define('APP_HOST', 'slim.local');

return [
    'app' => [
        'name' => 'Slim demo',
        'id' => 'slim.demo',
        'lang' => 'en',
        'charset' => 'UTF-8',
        'host' => APP_HOST,
    ],
    'logger' => [
        'path' => APP_ROOT . '/runtime/logs/app.log',
    ],
    'view' => [
        'path' => APP_ROOT . '/templates',
        'debug' => APP_DEBUG,
    ],
];
