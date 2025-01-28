<?php

declare(strict_types=1);

namespace OpenSearch;

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
    public static function create(array $options): ClientInterface;

}
