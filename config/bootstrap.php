<?php
/**
 * Create container instance
 */

require_once APP_ROOT . '/vendor/autoload.php';

use DI\ContainerBuilder;

$isTest = APP_ENV === 'test';

$builder = new ContainerBuilder();
$builder->addDefinitions(APP_ROOT . '/config/container.php');
if (!APP_DEBUG && !$isTest) {
    $builder->enableCompilation(APP_ROOT . '/runtime/cache');
}
return $builder->build();

