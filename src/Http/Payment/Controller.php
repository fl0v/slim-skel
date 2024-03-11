<?php

namespace App\Http\Payment;

use App\Helper\ControllerInterface;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

final class Controller implements ControllerInterface
{
    public static function routes(App $app): void
    {
        $app->group('/payment', function (Group $group) {
            $group->any('/form', PaymentFormAction::class);
            $group->post('/charge', PaymentChargeAction::class);
            //            $group->group('/auth', function (Group $group) {
            //                $group->post('/start.html', AuthStart::class);
            //                $group->post('/fingerprint.html', AuthFingerprint::class);
            //                $group->post('/challenge.html', AuthChallenge::class);
            //                $group->post('/finalise.html', AuthFinalise::class);
            //            });
        });
    }
}
