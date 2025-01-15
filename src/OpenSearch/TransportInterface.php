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
     * @param array<string, mixed> $params
     * @param string|array<string, mixed>|null $body
     * @param array<string, string> $headers
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \OpenSearch\Exception\HttpExceptionInterface
     */
    public function sendRequest(
        string $method,
        string $uri,
        array $params = [],
        string|array|null $body = null,
        array $headers = [],
    ): array|string|null;

}
