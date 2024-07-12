<?php

/**
 * Setup available console commands.
 */

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\Console\Application as Console;

return function (Console $console, Container $container) {
    $console->add($container->get(App\Commands\DemoCommand::class));
    $console->add($container->get(App\Commands\ApiDemoCommand::class));
};
