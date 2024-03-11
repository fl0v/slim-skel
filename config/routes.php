<?php

use Slim\App;

return static function (App $app) {
    \App\Http\Home\HomeController::routes($app);
    \App\Http\Payment\Controller::routes($app);
};
