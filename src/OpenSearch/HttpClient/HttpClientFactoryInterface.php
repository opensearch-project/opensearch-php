<?php

declare(strict_types=1);

namespace OpenSearch\HttpClient;

use Psr\Http\Client\ClientInterface;

/**
 * Interface for OpenSearch client factories.
 */
interface HttpClientFactoryInterface
{
    /**
     * Build the OpenSearch client.
     *
     * @param array<string,mixed> $options
     */
    public function create(array $options): ClientInterface;

}
