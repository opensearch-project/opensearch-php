<?php

namespace OpenSearch;

/**
 * Creates an OpenSearch client.
 */
interface ClientFactoryInterface
{
    /**
     * Creates a new OpenSearch client.
     *
     * @param array<string,mixed> $options
     *   The options to use when creating the client. The options are specific to the HTTP client implementation.
     */
    public function create(array $options): Client;
}
