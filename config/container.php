<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        (require __DIR__ . '/routes.php')($app);
        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },
];
