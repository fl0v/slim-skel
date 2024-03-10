<?php

namespace App\Action;

use App\Helper\Config;
use App\Helper\View;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractAction
{
    public function __construct(
        protected LoggerInterface $logger,
        protected ContainerInterface $container,
        protected Config $config,
        protected View $view,
    ) {}

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
