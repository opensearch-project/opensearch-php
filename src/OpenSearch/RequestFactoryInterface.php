<?php

namespace OpenSearch;

use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    /**
     * Create a new request.
     */
    public function createRequest(
        string $method,
        string $uri,
        array $params = [],
        string|array|null $body = null,
        array $headers = [],
    ): RequestInterface;
}
