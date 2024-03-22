<?php

namespace App\Http;

use App\Helper\Config;
use App\Helper\View;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

abstract class AbstractAction
{
    public function __construct(
        protected Container $container,
    ) {
    }

    protected function render(Response $response, string $viewFile, array $data = [], ?string $action = null): Response
    {
        $content = $this->getView()->fetch($viewFile, $data, true);

        if (APP_DEBUG) {
            $caller = $action ?: get_class($this);
            $this->getLogger()->debug(
                $caller,
                [
                    'view' => $viewFile,
                    'layout' => $this->getView()->getLayout(),
                    'size' => strlen($content),
                ]
            );
        }

        $response->getBody()->write($content);

        return $response;
    }

    /**
     * @TODO add content negotiation xml/json + pretty json on devel
     *
     * @param Response $response
     * @param array $data
     * @param ?string $action
     */
    protected function returnData(Response $response, array $data, ?string $action = null): Response
    {
        $json = (string)json_encode($data);

        if (APP_DEBUG) {
            $caller = $action ?: get_class($this);
            $this->getLogger()->debug($caller, [
                'response' => $data,
            ]);
        }

        $response->getBody()->write($json);

        return $response
            ->withHeader('Content-type', 'application/json');
    }

    protected function getContainer(): Container
    {
        return $this->container;
    }

    protected function getLogger(): Logger
    {
        return $this->getContainer()->get(Logger::class);
    }

    protected function getConfig(): Config
    {
        return $this->getContainer()->get(Config::class);
    }

    protected function getView(): View
    {
        return $this->getContainer()->get(View::class);
    }

    protected function getSession(): Session
    {
        return $this->getContainer()->get(Session::class);
    }
}
