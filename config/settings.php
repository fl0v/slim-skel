<?php

return [
    'app' => [
        'name' => 'Slim demo',
        'id' => 'slim.demo',
        'lang' => 'en',
        'charset' => 'UTF-8',
        'host' => APP_HOST,
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
];
