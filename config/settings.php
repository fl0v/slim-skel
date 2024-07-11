<?php

$appId = $_ENV['APP_ID'] ?? 'app';
$appVersion = @include 'version.php';

return [
    'app' => [
        'name' => 'Slim demo',
        'id' => $appId,
        'lang' => 'en',
        'charset' => 'UTF-8',
        'host' => $_ENV['APP_HOST'] ?? 'localhost',
        'version' => $appVersion,
    ],
    'error' => [
        'displayErrorDetails' => $_ENV['APP_DEBUG'] ?? false,
        'logError' => true,
        'logErrorDetails' => true,
    ],
    'logger' => [
        'path' => APP_ROOT . '/runtime/logs/app.log',
    ],
    'view' => [
        'path' => APP_ROOT . '/resources/templates',
        'layout' => 'main.php', // default layout
        'debug' => APP_DEBUG,
    ],
    'cache' => [
        'namespace' => $appId . '.' . $appVersion,
        'ttl' => 3600, // default
        // memcache
        'hosts' => explode(',', $_ENV['MEMCACHE_HOSTS'] ?? ''),
        // filesystem
        'path' => APP_ROOT . '/runtime/cache',
    ],
    'doctrine' => [
        'debug' => APP_DEBUG, // in debug mode will not cache metadata
        'metadata_paths' => [APP_ROOT . '/src/Db'], // will search for yml, xml, annotations, comments, attributes
        'connection' => [
            'driver' => $_ENV['MYSQL_DRIVER'] ?? 'pdo_mysql',
            'host' => $_ENV['MYSQL_HOST'] ?? 'localhost',
            'port' => $_ENV['MYSQL_PORT'] ?? 3306,
            'dbname' => $_ENV['MYSQL_DBNAME'] ?? '',
            'user' => $_ENV['MYSQL_USER'] ?? '',
            'password' => $_ENV['MYSQL_PASSWORD'] ?? '',
            'charset' => 'utf8mb4',
        ],
        'migrations' => [
            'table_storage' => [
                'table_name' => 'migrations',
                'version_column_name' => 'version',
                'version_column_length' => 191,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],
            'migrations_paths' => [
                'App\Migrations' => APP_ROOT . '/resources/db/migrations',
            ],
            'all_or_nothing' => true,
            'transactional' => true,
            'check_database_platform' => true,
            'organize_migrations' => 'none',
            'connection' => null,
            'em' => null,
        ],
        'mongodb' => [
            'dsn' => $_ENV['MONGODB_DSN'] ?? 'mongodb://localhost',
            'documents' => [APP_ROOT . '/src/Mongo/Documents'], // where all mongo mapped entities will be located
            'hydrators' => APP_ROOT . '/src/Mongo/Hydrators',
            'hydratorsNamespace' => '\App\Mongo\Hydrators',
        ],
    ],
];
