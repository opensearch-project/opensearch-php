<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * OpenSearch PHP client
 *
 * @link      https://github.com/opensearch-project/opensearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch;

use OpenSearch\ConnectionPool\AbstractConnectionPool;
use OpenSearch\Connections\ConnectionFactoryInterface;
use OpenSearch\Namespaces\NamespaceBuilderInterface;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Serializers\SmartSerializer;
use Psr\Log\LoggerInterface;

class ClientBuilder
{
    /**
     * The serializer.
     */
    private ?SerializerInterface $serializer = null;

    /**
     * The endpoint factory.
     */
    private ?EndpointFactoryInterface $endpointFactory = null;

    /**
     * The transport.
     */
    private ?TransportInterface $transport = null;

    /**
     * @var NamespaceBuilderInterface[]
     */
    private array $registeredNamespacesBuilders = [];

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Create an instance of ClientBuilder
     *
     * @deprecated in 2.3.1 and will be removed in 3.0.0.
     */
    public static function create(): ?ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        return null;
    }

    /**
     * Gets the serializer instance. If not set, it will create a new instance of SmartSerializer.
     */
    private function getSerializer(): SerializerInterface
    {
        return $this->serializer ?? $this->serializer = new SmartSerializer();
    }

    /**
     * Can supply second param to Client::__construct() when invoking manually or with dependency injection
     *
     * @deprecated in 2.3.2 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::getEndpointFactory() instead.
     */
    public function getEndpoint(): callable
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.2 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::getEndpointFactory() instead.', E_USER_DEPRECATED);
        return fn ($c) => $this->endpointFactory->getEndpoint('OpenSearch\\Endpoints\\' . $c);
    }

    /**
     * Can supply third param to Client::__construct() when invoking manually or with dependency injection
     *
     * @return NamespaceBuilderInterface[]
     */
    public function getRegisteredNamespacesBuilders(): array
    {
        return $this->registeredNamespacesBuilders;
    }

    /**
     * Build a new client from the provided config.  Hash keys
     * should correspond to the method name e.g. ['connectionPool']
     * corresponds to setConnectionPool().
     *
     * Missing keys will use the default for that setting if applicable
     *
     * Unknown keys will throw an exception by default, but this can be silenced
     * by setting `quiet` to true
     *
     * @param  array $config
     * @param  bool $quiet False if unknown settings throw exception, true to silently
     *                     ignore unknown settings
     * @throws Common\Exceptions\RuntimeException
     *
     * @deprecated in 2.3.1 and will be removed in 3.0.0.
     */
    public static function fromConfig(array $config, bool $quiet = false): Client
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        $builder = new self();
        return $builder->build();
    }

    /**
     * Get the default handler
     *
     * @param array $multiParams
     * @param array $singleParams
     *
     * @deprecated in 2.3.1 and will be removed in 3.0.0.
     */
    public static function defaultHandler(array $multiParams = [], array $singleParams = []): callable
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        return fn() => @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
    }

    /**
     * Get the multi handler for async (CurlMultiHandler)
     *
     * @throws \RuntimeException
     */
    public static function multiHandler(array $params = []): ?CurlMultiHandler
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        return null;
    }

    /**
     * Get the handler instance (CurlHandler)
     *
     * @throws \RuntimeException
     */
    public static function singleHandler(): ?CurlHandler
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        return null;
    }

    /**
     * Set connection Factory
     *
     * @param ConnectionFactoryInterface $connectionFactory
     */
    public function setConnectionFactory(ConnectionFactoryInterface $connectionFactory): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        return $this;
    }

    /**
     * Set the connection pool (default is StaticNoPingConnectionPool)
     *
     * @param  AbstractConnectionPool|string $connectionPool
     * @param array $args
     * @throws \InvalidArgumentException
     */
    public function setConnectionPool($connectionPool, array $args = []): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        return $this;
    }

    /**
     * Set the endpoint
     *
     * @param callable $endpoint
     *
     * @deprecated in 2.3.2 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::setEndpointFactory() instead.
     */
    public function setEndpoint(callable $endpoint): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.2 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::setEndpointFactory() instead.', E_USER_DEPRECATED);
        $this->endpointFactory = new LegacyEndpointFactory($endpoint);

        return $this;
    }

    public function setEndpointFactory(EndpointFactoryInterface $endpointFactory): ClientBuilder
    {
        $this->endpointFactory = $endpointFactory;
        return $this;
    }

    /**
     * Register namespace
     *
     * @param NamespaceBuilderInterface $namespaceBuilder
     */
    public function registerNamespace(NamespaceBuilderInterface $namespaceBuilder): ClientBuilder
    {
        $this->registeredNamespacesBuilders[] = $namespaceBuilder;

        return $this;
    }

    /**
     * Set the transport
     *
     * @param Transport $transport
     *
     * @deprecated in 2.3.1 and will be removed in 3.0.0.
     */
    public function setTransport(TransportInterface $transport): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);
        $this->transport = $transport;

        return $this;
    }

    public function getTransport(): TransportInterface
    {
        return $this->transport;
    }

    /**
     * Set the HTTP handler (cURL is default)
     *
     * @param  mixed $handler
     *
     * @deprecated in 2.3.1 and will be removed in 3.0.0.
     */
    public function setHandler($handler): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);

        return $this;
    }

    /**
     * Set the PSR-3 Logger
     *
     * @param LoggerInterface $logger
     *
     * @deprecated in 2.3.1 and will be removed in 3.0.0.
     */
    public function setLogger(LoggerInterface $logger): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);

        return $this;
    }

    /**
     * Set the PSR-3 tracer
     *
     * @param LoggerInterface $tracer
     *
     * @deprecated in 2.3.1 and will be removed in 3.0.0.
     */
    public function setTracer(LoggerInterface $tracer): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.3.1 and will be removed in 3.0.0.', E_USER_DEPRECATED);

        return $this;
    }

    /**
     * Set the serializer
     */
    public function setSerializer(SerializerInterface $serializer): static
    {
        $this->serializer = $serializer;

        return $this;
    }

    private function getEndpointFactory(): EndpointFactoryInterface
    {
        if ($this->endpointFactory) {
            return $this->endpointFactory;
        }
        return $this->endpointFactory = new EndpointFactory($this->getSerializer());
    }

    /**
     * Build and returns the Client object
     */
    public function build(): Client
    {
        return new Client($this->getTransport(), $this->getEndpointFactory(), $this->getRegisteredNamespacesBuilders());
    }

}
