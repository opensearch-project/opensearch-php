<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Exception thrown when a 503 Service Unavailable HTTP error occurs.
 */
class ServiceUnavailableHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(503, $message, $headers, $code, $previous);
    }
}
