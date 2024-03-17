<?php

return [
    'app' => [
        'name' => 'Slim demo',
        'id' => 'slim.demo',
        'lang' => 'en',
        'charset' => 'UTF-8',
        'host' => APP_HOST,
        'version' => APP_VERSION,
    ],
    'error' => [
        'displayErrorDetails' => APP_DEBUG,
        'logError' => true,
        'logErrorDetails' => true,
    ],
    'logger' => [
        'path' => APP_ROOT . '/runtime/logs/app.log',
    ],
    'view' => [
        'path' => APP_ROOT . '/templates',
        'layout' => 'main.php', // default layout
        'debug' => APP_DEBUG,
    ],
    'cache' => [
        'prefix' => 'demo', // add APP_VERSION to invalidate cache on release
        'ttl' => 3600,
    ],
];
