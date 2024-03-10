<?php

declare(strict_types=1);

namespace App\Helper;

class Config
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @throws \Exception
     */
    public function get(string $key = '', mixed $default = null): mixed
    {
        return ArrayHelper::getValue($this->config, $key, $default);
    }
}
