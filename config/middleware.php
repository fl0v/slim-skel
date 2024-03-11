<?php

use Slim\App;

return function (App $app) {
    $app->addBodyParsingMiddleware(); // Parse json, form data and xml
    $app->addRoutingMiddleware(); // Add the Slim built-in routing middleware
    $app->addErrorMiddleware(true, true, true); // Handle exceptions
    $app->add(\App\Helper\LoggingMiddleware::class);
};
