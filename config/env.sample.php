<?php

// phpcs:disable

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors', '0');

\defined('APP_ENV') or \define('APP_ENV', 'production');
\defined('APP_DEBUG') or \define('APP_DEBUG', false);
\defined('APP_ROOT') or \define('APP_ROOT', dirname(__DIR__));
\defined('APP_HOST') or \define('APP_HOST', 'slim.local');
\defined('APP_VERSION') or \define('APP_VERSION', include (APP_ROOT . '/version.php'));

\defined('MEMCACHE_HOSTS') or \define('MEMCACHE_HOSTS', 'localhost:11211');
