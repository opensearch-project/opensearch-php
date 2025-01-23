<?php

declare(strict_types=1);

namespace OpenSearch;

use OpenSearch\Endpoints\AbstractEndpoint;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(LegacyEndpointFactory::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * Provides a endpoint factory using a legacy callable.
 *
 * @deprecated in 2.4.0 and will be removed in 3.0.0. Use PsrTransport instead.
 */
class LegacyEndpointFactory implements EndpointFactoryInterface
{
    /**
     * The endpoints callable.
     *
     * @var callable
     */
    protected $endpoints;

    public function __construct(callable $endpoints)
    {
        $this->endpoints = $endpoints;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(string $class): AbstractEndpoint
    {
        // We need to strip the base namespace from the class name for BC.
        $class = str_replace('OpenSearch\\Endpoints\\', '', $class);
        $endpointBuilder = $this->endpoints;
        return $endpointBuilder($class);
    }

}
