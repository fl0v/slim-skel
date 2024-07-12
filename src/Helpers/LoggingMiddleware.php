<?php

declare(strict_types=1);

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class LoggingMiddleware implements MiddlewareInterface
{
    private const REQUEST_LOG = '{method} {uri} {encoding}';
    private const RESPONSE_LOG = '{statusCode} {reasonPhrase} {encoding}';

    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $this->logger->debug(strtr(self::REQUEST_LOG, [
            '{method}' => $request->getMethod(),
            '{uri}' => $request->getUri(),
            '{encoding}' => $request->getHeader('Content-Type')[0] ?? '',
        ]));

        $response = $handler->handle($request);

        $this->logger->debug(strtr(self::RESPONSE_LOG, [
            '{statusCode}' => $response->getStatusCode(),
            '{reasonPhrase}' => $response->getReasonPhrase(),
            '{encoding}' => $response->getHeader('Content-Type')[0] ?? '',
        ]));

        return $response;
    }
}
