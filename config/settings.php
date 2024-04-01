<?php

return [
    'app' => [
        'name' => 'Slim demo',
        'id' => APP_ID,
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
        'namespace' => APP_ID . '.' . APP_VERSION,
        'ttl' => 3600, // default
        // memcache
        'hosts' => explode(',', MEMCACHE_HOSTS),
        // filesystem
        'path' => APP_ROOT . '/runtime/cache',
    ],
     'doctrine' => [
        'debug' => APP_DEBUG, // in debug mode will not cache metadata
        'metadata_paths' => [APP_ROOT . '/src/Db'], // will search for yml, xml, anotations, comments, attributes
        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => 'mysql',
            'port' => 3306,
            'dbname' => 'slimdemo',
            'user' => 'docker',
            'password' => 'password',
            'charset' => 'utf8mb4'
        ],
    ],
];
