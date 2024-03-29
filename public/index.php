<?php

declare(strict_types=1);

use Slim\App;

include_once __DIR__ . '/../config/env.php';
$container  = require APP_ROOT . '/config/bootstrap.php';

/** @var App $app */
$app = $container->get(App::class);
$app->run();
