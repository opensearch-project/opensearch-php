<?php

namespace OpenSearch\Exception;

/**
 * Exception thrown when a 429 Too Many Requests HTTP error occurs.
 */
class TooManyRequestsHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(429, $message, $headers, $code, $previous);
    }

}
