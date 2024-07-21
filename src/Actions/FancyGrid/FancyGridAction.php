<?php

declare(strict_types=1);

namespace App\Actions\FancyGrid;

use App\Actions\AbstractAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FancyGridAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $fancyGrid = $this->getFancyGridBuilder()->buildFancyGrid(
            // ...
        );

        return $this->render($response, 'FancyGrid/index.php', [
            'fancyGrid' => $fancyGrid,
        ], __METHOD__);
    }
}
