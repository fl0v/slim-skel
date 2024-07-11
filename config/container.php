<?php

/**
 * Container definitions.
 */

use App\Helper\Config;
use App\Helper\View;
use Doctrine\DBAL\Connection;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Configuration;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface as Logger;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
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

    /*
     * Http
     */

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

    /*
     * DB
     */

    Configuration::class => function (Container $container, Config $config) {
        $settings = $config->get('doctrine');
        $debug = $settings['debug'] ?? false;
        $metadataPaths = $settings['metadata_paths'];
        $cache = $debug
            ? new ArrayAdapter()
            : $container->get('cache:filesystem');

        return Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration($metadataPaths, $debug, null, $cache);
    },

    Connection::class => function (Container $container, Config $config) {
        $settings = $config->get('doctrine');

        return Doctrine\DBAL\DriverManager::getConnection(
            $settings['connection'] ?? [],
            $container->get(Configuration::class),
        );
    },

    EntityManagerInterface::class => DI\get(EntityManager::class),

    /*
     * MongoDb
     */

    DocumentManager::class => function (Container $container, Config $config) {
        $settings = $config->get('doctrine.mongodb');

        $client = new \MongoDB\Client($settings['dsn'] ?? '', [], ['typeMap' => DocumentManager::CLIENT_TYPEMAP]);
        $config = new \Doctrine\ODM\MongoDB\Configuration();
        $config->setHydratorDir($settings['hydrators'] ?? '');
        $config->setHydratorNamespace($settings['hydratorsNamespace'] ?? '');
        if (!empty($settings['documents'])) {
            $config->setMetadataDriverImpl(AnnotationDriver::create($settings['documents']));
        }

        // ...

        return DocumentManager::create($client, $config);
    },

    /*
     * Cache
     */

    //    CacheItemPoolInterface::class => function (Container $container, Config $config) {
    //        //$cache = $container->get(MemcachedAdapter::class);
    //        $cache = new MemcachedAdapter();
    //
    //    },

    'cache:filesystem' => function (Config $config) {
        $settings = $config->get('cache');
        $ns = $settings['namespace'] ?? $config->get('app.id');
        $ttl = $settings['ttl'] ?? 3600;
        $path = $settings['path'] ?? null;

        return new FilesystemAdapter($ns, $ttl, $path);
    },

    /*
     * Other
     */

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
