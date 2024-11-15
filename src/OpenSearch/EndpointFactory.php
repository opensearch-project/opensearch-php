<?php

namespace OpenSearch;

use OpenSearch\Endpoints\AbstractEndpoint;
use OpenSearch\Serializers\SerializerInterface;
use ReflectionClass;

/**
 * A factory for creating endpoints.
 */
class EndpointFactory implements EndpointFactoryInterface
{
    /**
     * @var array<string, AbstractEndpoint>
     */
    private array $endpoints = [];

    public function __construct(
        protected SerializerInterface $serializer,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(string $class): AbstractEndpoint
    {
        if (!isset($this->endpoints[$class])) {
            $this->endpoints[$class] = $this->createEndpoint($class);
        }

        return $this->endpoints[$class];
    }

    /**
     * Creates an endpoint.
     *
     * @phpstan-template T of AbstractEndpoint
     * @phpstan-param class-string<T> $class
     * @phpstan-return T
     * @throws \ReflectionException
     */
    private function createEndpoint(string $class): AbstractEndpoint
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor && $constructor->getParameters()) {
            return new $class($this->serializer);
        }
        return new $class();
    }

}
