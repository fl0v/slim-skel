<?php

namespace App\Helper;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class LoggingMiddleware implements MiddlewareInterface
{
    private const REQUEST_LOG = '{method} {uri}';
    private const RESPONSE_LOG = '{statusCode} {reasonPhrase}';

    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $this->logger->info(strtr(self::REQUEST_LOG, [
            '{method}' => $request->getMethod(),
            '{uri}' => $request->getUri(),
        ]));

        $response = $handler->handle($request);

        $this->logger->info(strtr(self::RESPONSE_LOG, [
            '{statusCode}' => $response->getStatusCode(),
            '{reasonPhrase}' => $response->getReasonPhrase(),
        ]));

        return $response;
    }
}
