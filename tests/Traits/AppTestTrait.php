<?php

namespace App\Test\Traits;

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
        $container = require __DIR__ . '/../../config/bootstrap.php';

        $this->app = $container->get(App::class);
        $this->setUpContainer($container);

        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'setUpDatabase')) {
            // $this->setUpDatabase(__DIR__ . '/../../resources/schema/schema.sql');
            // @TODO run migrations
        }
    }
}
