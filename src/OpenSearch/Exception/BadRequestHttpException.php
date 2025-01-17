<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Exception thrown when a 400 Bad Request HTTP error occurs.
 */
class BadRequestHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(400, $message, $headers, $code, $previous);
    }

}
