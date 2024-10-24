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

use OpenSearch\Namespaces\NamespaceBuilderInterface;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Serializers\SmartSerializer;

class ClientBuilder
{
    /**
     * The serializer.
     */
    private ?SerializerInterface $serializer = null;

    /**
     * The endpoint factory.
     */
    private ?EndpointFactory $endpointFactory = null;

    /**
     * Create a new ClientBuilder.
     */
    public function __construct(protected readonly TransportInterface $transport)
    {
    }

    /**
     * Gets the serializer instance. If not set, it will create a new instance of SmartSerializer.
     */
    private function getSerializer(): SerializerInterface
    {
        if ($this->serializer) {
            return $this->serializer;
        }
        return $this->serializer = new SmartSerializer();
    }

    /**
     * Set the serializer
     */
    public function setSerializer(SerializerInterface $serializer): static
    {
        $this->serializer = $serializer;
        return $this;
    }

    public function setEndpointFactory(?EndpointFactory $endpointFactory): static
    {
        $this->endpointFactory = $endpointFactory;
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
        return new Client($this->transport, $this->getEndpointFactory());
    }

}
