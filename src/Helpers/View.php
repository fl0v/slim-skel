<?php

declare(strict_types=1);

namespace App\Helpers;

use Psr\Container\ContainerInterface as Container;

class View extends \Slim\Views\PhpRenderer
{
    protected bool $debug = false;
    protected Config $config;
    protected Container $container;

    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
        $this->addAttribute('debug', $debug);
    }

    public function getDebug(): bool
    {
        return $this->debug;
    }

    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function withLayout(string $layout = ''): static
    {
        $this->setLayout($layout);

        return $this;
    }
}
