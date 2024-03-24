<?php

use App\Helper\Config;
use App\Helper\View;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

return [
    Config::class => function () {
        return new Config(include __DIR__ . '/settings.php');
    },

    Console::class => function (Container $container) {
        $app = new Console();

        (require __DIR__ . '/commands.php')($app, $container);

        return $app;
    },

    App::class => function (Container $container, Config $config) {
        $app = AppFactory::createFromContainer($container);

        (require __DIR__ . '/routes.php')($app, $config);
        (require __DIR__ . '/middleware.php')($app, $config);

        return $app;
    },

    ServerRequestFactoryInterface::class => function (Container $container) {
        return $container->get(Slim\Psr7\Factory\ServerRequestFactory::class);
    },

    ResponseFactoryInterface::class => function (Container $container) {
        return $container->get(Slim\Psr7\Factory\ResponseFactory::class);
    },

    StreamFactoryInterface::class => function (Container $container) {
        return $container->get(Slim\Psr7\Factory\StreamFactory::class);
    },

    UploadedFileFactoryInterface::class => function (Container $container) {
        return $container->get(Slim\Psr7\Factory\UploadedFileFactory::class);
    },

    UriFactoryInterface::class => function (Container $container) {
        return $container->get(Slim\Psr7\Factory\UriFactory::class);
    },

    // Slim\Psr7\Interfaces\HeadersInterface::class => function (Container $container) {
    //     return $container->get(Slim\Psr7\Headers::class);
    // },

    Session::class => function (Container $container) {
        $session = $container->get(Symfony\Component\HttpFoundation\Session\Session::class);
        $session->start();

        return $session;
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

    Logger::class => function (Config $config) {
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
];
