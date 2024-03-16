<?php

namespace App\Http;

use App\Helper\Config;
use App\Helper\View;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;

abstract class AbstractAction
{
    public function __construct(
        protected LoggerInterface $logger,
        protected ContainerInterface $container,
        protected Config $config,
        protected View $view,
    ) {
    }

    protected function encodeData(Response $response, array $data, ?string $action = null): Response
    {
        $json = (string)json_encode($data);

        if (APP_DEBUG) {
            $caller = get_class($this) . ($action ? '::' . $action : '');
            $this->getLogger()->debug('{caller}', [
                'caller' => $action,
                'response' => $data,
            ]);
        }
        
        $response->getBody()->write($json);
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    protected function getConfig(): Config
    {
        return $this->config;
    }

    protected function getView(): View
    {
        return $this->view;
    }
}
