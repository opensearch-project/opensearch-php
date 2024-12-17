<?php

namespace OpenSearch;

use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    /**
     * Create a new request.
     *
     * @param array<string, mixed> $params
     * @param string|array<string, mixed>|null $body
     * @param array<string, string> $headers
     */
    public function createRequest(
        string $method,
        string $uri,
        array $params = [],
        string|array|null $body = null,
        array $headers = [],
    ): RequestInterface;
}
