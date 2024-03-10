<?php

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/', \App\Action\HomeAction::class);
    $app->get('/ping', \App\Action\PingAction::class);

    $app->group('/payment', function (Group $group) {
        $group->post('/form', PaymentFormAction::class);
        $group->post('/charge', PaymentChargeAction::class);
        $group->group('/auth', function (Group $group) {
            $group->post('/start.html', AuthStart::class);
            $group->post('/fingerprint.html', AuthFingerprint::class);
            $group->post('/challenge.html', AuthChallenge::class);
            $group->post('/finalise.html', AuthFinalise::class);
        });
    });
};
