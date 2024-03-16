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
