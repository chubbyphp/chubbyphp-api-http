<?php

declare(strict_types=1);

namespace Chubbyphp\ApiHttp\Middleware;

use Chubbyphp\ApiHttp\Manager\ResponseManagerInterface;
use Chubbyphp\HttpException\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class ApiExceptionMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private ResponseManagerInterface $responseManager,
        private bool $debug = false,
        ?LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $exception) {
            return $this->handleException($request, $exception);
        }
    }

    private function handleException(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
    {
        $backtrace = $this->backtrace($exception);

        $this->logger->error('Exception', ['backtrace' => $backtrace]);

        if (null === $accept = $request->getAttribute('accept')) {
            throw $exception;
        }

        if ($this->debug) {
            $httpException = HttpException::createInternalServerError([
                'detail' => $exception->getMessage(),
                'backtrace' => $backtrace,
            ]);
        } else {
            $httpException = HttpException::createInternalServerError();
        }

        return $this->responseManager->createFromHttpException($httpException, $accept);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function backtrace(\Throwable $exception): array
    {
        $exceptions = [];
        do {
            $exceptions[] = [
                'class' => $exception::class,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        } while ($exception = $exception->getPrevious());

        return $exceptions;
    }
}
