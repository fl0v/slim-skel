<?php

declare(strict_types=1);

namespace App\Helpers;

class View extends \Slim\Views\PhpRenderer
{
    protected bool $debug = false;
    protected Config $config;

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

    public function withLayout(string $layout = ''): static
    {
        $this->setLayout($layout);

        return $this;
    }
}
