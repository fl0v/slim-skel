<?php

namespace App\Http\Payment;

use App\Http\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PaymentChargeAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $data = [
            'app' => $this->getConfig()->get('app'),
        ];
        $this->getLogger()->debug(__CLASS__, [
            'response' => $data,
        ]);

        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-type', 'application/json');
    }
}
