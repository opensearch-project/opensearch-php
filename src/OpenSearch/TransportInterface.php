<?php

namespace OpenSearch;

/**
 * Provides an interface for sending OpenSearch requests.
 */
interface TransportInterface
{
    /**
     * Send a request.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function sendRequest(Request $request): Response;

}
