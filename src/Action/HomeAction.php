<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->getView()
            ->withLayout('main.php')
            ->render($response, 'action/home.php', [
                'data' => 'asd',
            ]);
    }
}
