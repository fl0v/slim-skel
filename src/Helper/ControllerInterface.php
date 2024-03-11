<?php

namespace App\Helper;

use Slim\App;

interface ControllerInterface
{
    public static function routes(App $app): void;
}
