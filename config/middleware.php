<?php

use App\Helper\Config;
use Slim\App;

return function (App $app, Config $config) {
    $app->addBodyParsingMiddleware(); // Parse json, form data and xml
    $app->addRoutingMiddleware(); // Add the Slim built-in routing middleware

    $errorSettings = $config->get('error');
    $app->addErrorMiddleware(
        $errorSettings['displayErrorDetails'] ?? false,
        $errorSettings['logErrors'] ?? true,
        $errorSettings['logErrorDetails'] ?? true,
    ); // Handle exceptions

    $app->add(\App\Helper\LoggingMiddleware::class);
};
