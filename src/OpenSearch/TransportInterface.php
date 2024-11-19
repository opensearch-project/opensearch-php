<?php

namespace OpenSearch;

/**
 * Provides an interface for sending OpenSearch requests.
 */
interface TransportInterface
{
    /**
     * Create a new request.
     *
     * @throws \Exception
     */
    public function sendRequest(
        string $method,
        string $uri,
        array $params = [],
        mixed $body = null,
        array $headers = [],
    ): array|string|null;

}
