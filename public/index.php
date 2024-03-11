<?php

include_once __DIR__ . '/../config/env.php';
require_once APP_ROOT . '/vendor/autoload.php';

use DI\ContainerBuilder;
use Slim\App;

// Build DI container instance
$builder = new ContainerBuilder();
$builder->addDefinitions(APP_ROOT . '/config/container.php');
if (!APP_DEBUG) {
    $builder->enableCompilation(APP_ROOT . '/runtime/cache');
}
$container = $builder->build();

// Create App instance
$app = $container->get(App::class);
$app->run();
