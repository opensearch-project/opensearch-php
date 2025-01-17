<?php

namespace OpenSearch\Exception;

class NotAcceptableHttpException extends HttpException
{
    public function __construct(string $message = '', array $headers = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(406, $message, $headers, $code, $previous);
    }
}
