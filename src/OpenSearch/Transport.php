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

use Http\Client\HttpAsyncClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Http\Promise\Promise;
use OpenSearch\Common\Exceptions\NoAsyncClientException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Transport implements TransportInterface
{
    private ?HttpAsyncClient $asyncClient = null;

    /**
     * Transport class is responsible for dispatching requests to the
     * underlying cluster connections
     */
    public function __construct(
        protected ClientInterface $client,
        protected RequestFactoryInterface $requestFactory,
    ) {
    }

    /**
     * Create a new request.
     */
    public function createRequest(string $method, string $uri, array $params = [], mixed $body = null): RequestInterface
    {
        return $this->requestFactory->createRequest($method, $uri, $params, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAsyncRequest(RequestInterface $request): Promise
    {
        $httpAsyncClient = $this->getAsyncClient();
        return $httpAsyncClient->sendAsyncRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }

    /**
     * Set the async client to use for async requests.
     */
    public function setAsyncClient(HttpAsyncClient $asyncClient): self
    {
        $this->asyncClient = $asyncClient;
        return $this;
    }

    /**
     * Get the async client to use for async requests.
     *
     * If no async client is set, the discovery mechanism will be used to find
     * an async client.
     *
     * @throws NoAsyncClientException
     */
    private function getAsyncClient(): HttpAsyncClient
    {
        if ($this->asyncClient) {
            return $this->asyncClient;
        }

        if ($this->client instanceof HttpAsyncClient) {
            return $this->asyncClient = $this->client;
        }

        try {
            return $this->asyncClient = HttpAsyncClientDiscovery::find();
        } catch (\Exception $e) {
            throw new NoAsyncClientException('No async HTTP client found. Install a package providing "php-http/async-client-implementation"', 0, $e);
        }
    }

}
