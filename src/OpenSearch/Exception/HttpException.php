<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Exception thrown when an HTTP error occurs.
 *
 * @phpstan-consistent-constructor
 */
class HttpException extends \RuntimeException implements HttpExceptionInterface
{
    public function __construct(
        protected readonly int $statusCode,
        string $message = '',
        protected readonly array $headers = [],
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

}
