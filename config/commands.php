<?php

use Psr\Container\ContainerInterface as Container;
use Symfony\Component\Console\Application as Console;

/**
 * Setup available console commands.
 */

return function (Console $console, Container $container) {
    $console->add($container->get(App\Command\DemoCommand::class));
    $console->add($container->get(App\Command\ApiDemoCommand::class));
};
