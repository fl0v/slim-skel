<?php

use App\Helper\Config;
use App\Http\Home\HomeController;
use App\Http\Payment\PaymentChargeAction;
use App\Http\Payment\PaymentFormAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return static function (App $app, Config $config) {
    $app->get('/', [HomeController::class, 'index']);
    $app->any('/ping', [HomeController::class, 'ping']);
    $app->any('/debug', [HomeController::class, 'debug']);

    $app->group('/payment', function (Group $group) {
        $group->any('/form', PaymentFormAction::class);
        $group->post('/charge', PaymentChargeAction::class);
        // $group->group('/auth', function (Group $group) {
        //     $group->post('/start.html', AuthStart::class);
        //     $group->post('/fingerprint.html', AuthFingerprint::class);
        //     $group->post('/challenge.html', AuthChallenge::class);
        //     $group->post('/finalise.html', AuthFinalise::class);
        // });
    });
};
