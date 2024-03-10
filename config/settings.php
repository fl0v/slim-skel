<?php

// Should be set to 0 in production
error_reporting(E_ALL);

// Should be set to '0' in production
ini_set('display_errors', '1');

return [
    'app' => [
        'name' => 'Slim demo',
        'id' => 'slim.demo',
        'lang' => 'en',
        'charset' => 'UTF-8',
        'host' => 'localhost:8080',
    ],
    'components' => [
        'logger' => [
            'path' => '/runtime/logs/app.log',
            'debug' => true,
        ],
        'view' => [
            'path' => '/templates',
            'debug' => true,
        ],
    ],
];
