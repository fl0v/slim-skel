<?php

use App\Helper\Config;
use App\Helper\View;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Console\Application as Console;

return [
    Config::class => function () {
        return new Config(include __DIR__ . '/settings.php');
    },

    App::class => function (Container $container) {
        $app = AppFactory::createFromContainer($container);

        (require __DIR__ . '/routes.php')($app);
        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },

     Console::class => function (Container $container) {
         $app = new Symfony\Component\Console\Application();
         (require __DIR__ . '/commands.php')($app, $container);
         return $app;
     },

    // HTTP factories
    ResponseFactoryInterface::class => function (Container $container) {
        return $container->get(Psr17Factory::class);
    },

    ServerRequestFactoryInterface::class => function (Container $container) {
        return $container->get(Psr17Factory::class);
    },

    LoggerInterface::class => function (Config $config) {
        $settings = $config->get('logger');
        $logger = new Monolog\Logger($settings['name'] ?? $config->get('app.id'));
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $logger->pushHandler(
            (new Monolog\Handler\RotatingFileHandler(
                $settings['path'],
                $settings['maxFiles'] ?? 3,
                $settings['level'] ?? Monolog\Logger::DEBUG,
            ))->setFormatter(
                new Monolog\Formatter\LineFormatter(null, null, false, true)
            ),
        );
        $logger->pushHandler(
            new Monolog\Handler\StreamHandler(
                $settings['path'],
                $settings['level'] ?? Monolog\Logger::DEBUG,
            ),
        );

        return $logger;
    },

    View::class => function (Config $config) {
        $settings = $config->get('view');
        $view = new View($settings['path']);
        $view->setAttributes($settings['attributes'] ?? []);
        $view->setLayout($settings['layout'] ?? '');
        $view->setDebug($settings['debug'] ?? false);
        $view->setConfig($config);

        return $view;
    },
];
