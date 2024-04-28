<?php

/**
 * Create container instance.
 */

require_once APP_ROOT . '/vendor/autoload.php';

use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions(APP_ROOT . '/config/container.php');
if (!APP_DEBUG && APP_ENV !== 'test') {
    $builder->enableCompilation(APP_ROOT . '/runtime/cache');
}

return $builder->build();
