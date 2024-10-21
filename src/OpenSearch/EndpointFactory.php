<?php

namespace OpenSearch;

use OpenSearch\Serializers\SerializerInterface;
use ReflectionClass;

/**
 * A factory for creating endpoints.
 */
class EndpointFactory implements EndpointFactoryInterface
{
    /**
     * @phpstan-template T of \OpenSearch\EndpointInterface
     * @phpstan-var array<string, T>
     */
    private array $endpoints = [];

    public function __construct(
        protected SerializerInterface $serializer,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(string $class): EndpointInterface
    {
        if (!isset($this->endpoints[$class])) {
            $this->endpoints[$class] = $this->createEndpoint($class);
        }

        return $this->endpoints[$class];
    }

    /**
     * Creates an endpoint.
     *
     * @phpstan-template T of EndpointInterface
     * @phpstan-param class-string<T> $class
     * @phpstan-return T
     * @throws \ReflectionException
     */
    private function createEndpoint(string $class): EndpointInterface
    {
        $fullPath = '\\OpenSearch\\Endpoints\\' . $class;

        $reflection = new ReflectionClass($fullPath);
        $constructor = $reflection->getConstructor();

        if ($constructor && $constructor->getParameters()) {
            return new $fullPath($this->serializer);
        }
        return new $fullPath();
    }

}
