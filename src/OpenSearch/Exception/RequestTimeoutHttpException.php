<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Exception thrown when a 408 Request Timeout HTTP error occurs.
 */
class RequestTimeoutHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(408, $message, $headers, $code, $previous);
    }
}
