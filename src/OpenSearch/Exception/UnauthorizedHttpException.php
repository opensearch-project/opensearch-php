<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Exception thrown when a 401 Unauthorized HTTP error occurs.
 */
class UnauthorizedHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(401, $message, $headers, $code, $previous);
    }

}
