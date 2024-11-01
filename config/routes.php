<?php

use App\Helpers\Config;
use App\Actions\Home\HomeController;
use App\Actions\Payment\PaymentChargeAction;
use App\Actions\Payment\PaymentFormAction;
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
    if (!APP_DEBUG && APP_ENV !== 'test') {
        $app->getRouteCollector()->setCacheFile(APP_ROOT . '/runtime/cache');
    }
};
