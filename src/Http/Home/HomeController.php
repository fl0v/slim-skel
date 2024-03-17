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
        $app->any('/debug', [self::class, 'debug']);
    }

    public function index(Request $request, Response $response): Response
    {
        $viewsCount = $this->getSession()->get('views', 0);
        $viewsCount++;
        $this->getSession()->set('views', $viewsCount);
        return $this->render($response, 'home/index.php', [
            'views' => $viewsCount,
        ], __METHOD__);
    }

    public function ping(Request $request, Response $response): Response
    {
        $data = [
            'app' => $this->getConfig()->get('app'),
        ];

        return $this->returnData($response, $data, __METHOD__);
    }

    public function debug(Request $request, Response $response): Response
    {
        return $this->render($response, 'home/debug.php', [], __METHOD__);
    }
}
