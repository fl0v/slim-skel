<?php

namespace App\Test\Traits;

use DI\ContainerBuilder;
use Slim\App;

trait AppTestTrait
{
    use ContainerTestTrait;
    use HttpTestTrait;
    use HttpJsonTestTrait;

    protected App $app;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        $this->setUpApp();
    }

    protected function setUpApp(): void
    {
        include_once __DIR__ . '/../../config/env.php';

        // Get container instance
        $container  = require APP_ROOT . '/config/bootstrap.php';

        $this->app = $container->get(App::class);
        $this->setUpContainer($container);

        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'setUpDatabase')) {
            $this->setUpDatabase(__DIR__ . '/../../resources/schema/schema.sql');
        }
    }
}
