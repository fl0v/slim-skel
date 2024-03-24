<?php
include_once __DIR__ . '/../config/env.php';

use Slim\App;

// Get container instance
$container  = require APP_ROOT . '/config/bootstrap.php';

// Create App instance
$app = $container->get(App::class);
$app->run();
