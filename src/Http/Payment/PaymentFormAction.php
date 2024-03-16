<?php

namespace App\Http\Payment;

use App\Http\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class PaymentFormAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->getView()
            ->render($response, 'payment/form.php', [
                'data' => 'xxx',
            ]);
    }
}
