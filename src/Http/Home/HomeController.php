<?php

namespace App\Http\Home;

use App\Helper\ControllerInterface;
use App\Http\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

final class HomeController extends AbstractAction implements ControllerInterface
{
    public static function routes(App $app): void
    {
        $app->get('/', [self::class, 'index']);
        $app->any('/ping', [self::class, 'ping']);
    }

    public function index(Request $request, Response $response): Response
    {
        return $this->getView()
            ->withLayout('main.php')
            ->render($response, 'home/index.php', [
                'data' => 'asd',
            ]);
    }

    public function ping(Request $request, Response $response): Response
    {
        $data = [
            'app' => $this->getConfig()->get('app'),
        ];
        $this->getLogger()->debug('ping', [
            'response' => $data,
        ]);

        $response->getBody()->write((string)json_encode($data));

        return $response
            ->withHeader('Content-type', 'application/json');
    }
}
