<?php

use Symfony\Component\Console\Input\ArgvInput;

return [
    \Symfony\Component\Console\Application::class => function () {
        //$env = (new ArgvInput())->getParameterOption(['--env', '-e'], 'dev');
        $_ENV['APP_ENV'] = APP_ENV;

        return new \Symfony\Component\Console\Application();
    },
];
