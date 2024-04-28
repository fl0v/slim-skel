<?php

/**
 * Create container instance.
 */

use DI\ContainerBuilder;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

\defined('APP_ROOT') or \define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv::createImmutable(APP_ROOT);
$dotenv->safeLoad();

define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', $_ENV['APP_DEBUG'] ?? false);

$builder = new ContainerBuilder();
$builder->addDefinitions(APP_ROOT . '/config/container.php');
if (!APP_DEBUG && APP_ENV !== 'test') {
    $builder->enableCompilation(APP_ROOT . '/runtime/cache');
}

return $builder->build();
