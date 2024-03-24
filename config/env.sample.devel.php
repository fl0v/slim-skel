<?php

// phpcs:disable

error_reporting(E_ALL);
ini_set('display_errors', '1');

\defined('APP_ENV') or \define('APP_ENV', 'devel');
\defined('APP_DEBUG') or \define('APP_DEBUG', true);
\defined('APP_ROOT') or \define('APP_ROOT', dirname(__DIR__));
\defined('APP_HOST') or \define('APP_HOST', 'localhost:8080');
\defined('APP_VERSION') or \define('APP_VERSION', include (APP_ROOT . '/version.php'));

\defined('MEMCACHE_HOSTS') or \define('MEMCACHE_HOSTS', 'memcached:11211');