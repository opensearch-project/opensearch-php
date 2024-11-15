<?php

namespace OpenSearch;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * A factory for creating endpoints.
 */
interface EndpointFactoryInterface
{
    /**
     * Gets an endpoint.
     *
     * @phpstan-template T of AbstractEndpoint
     * @phpstan-param class-string<T> $class
     * @phpstan-return T
     */
    public function getEndpoint(string $class): AbstractEndpoint;

}
