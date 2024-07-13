<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;

class Config
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    public function get(string $key = '', mixed $default = null): mixed
    {
        return ArrayHelper::get($this->config, $key, $default);
    }
}
