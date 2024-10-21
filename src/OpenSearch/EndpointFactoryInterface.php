<?php

namespace OpenSearch;

/**
 * A factory for creating endpoints.
 */
interface EndpointFactoryInterface
{
    /**
     * Gets an endpoint.
     *
     * @phpstan-template T of EndpointInterface
     * @phpstan-param class-string<T> $class
     * @phpstan-return T
 */
    public function getEndpoint(string $class): EndpointInterface;

}
