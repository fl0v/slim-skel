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

        // List of paths where Doctrine will search for metadata.
        // Metadata can be either YML/XML files or PHP classes annotated
        // with comments or PHP8 attributes.
        'entities_dir' => [APP_ROOT . '/src/Entity'],

        // The parameters Doctrine needs to connect to your database.
        // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
        // needs a 'path' parameter and doesn't use most of the ones shown in this example).
        // Refer to the Doctrine documentation to see the full list
        // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
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
