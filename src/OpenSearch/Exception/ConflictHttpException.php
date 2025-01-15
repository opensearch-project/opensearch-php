<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Exception thrown when a 409 Conflict HTTP error occurs.
 */
class ConflictHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(409, $message, $headers, $code, $previous);
    }
}
