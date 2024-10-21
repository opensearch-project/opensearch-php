<?php

namespace OpenSearch;

use Http\Client\HttpAsyncClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

interface TransportInterface extends ClientInterface, HttpAsyncClient
{
    /**
     * Create a new request.
     */
    public function createRequest(
        string $method,
        string $uri,
        array $params = [],
        mixed $body = null
    ): RequestInterface;

}
