<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PaymentChargeAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $data = [
            'app' => $this->getConfig()->get('app'),
        ];

        return $this->returnData($response, $data);
    }
}
