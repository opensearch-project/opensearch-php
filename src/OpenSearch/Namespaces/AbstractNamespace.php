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

namespace OpenSearch\Namespaces;

use Http\Promise\Promise;
use OpenSearch\EndpointFactoryInterface;
use OpenSearch\EndpointInterface;
use OpenSearch\TransportInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractNamespace
{
    public function __construct(
        protected readonly TransportInterface $transport,
        protected readonly EndpointFactoryInterface $endpointFactory,
    ) {
    }

    protected bool $isAsync = false;

    /**
     * Check if the client is running in async mode.
     */
    public function isAsync(): bool
    {
        return $this->isAsync;
    }

    /**
     * Set the client to run in async mode.
     */
    public function setAsync(bool $isAsync): static
    {
        $this->isAsync = $isAsync;
        return $this;
    }

    /**
     * @return null|mixed
     */
    public function extractArgument(array &$params, string $arg)
    {
        if (array_key_exists($arg, $params) === true) {
            $val = $params[$arg];
            unset($params[$arg]);
            return $val;
        } else {
            return null;
        }
    }

    /**
     * Perform the request.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface|\Exception
     */
    protected function performRequest(EndpointInterface $endpoint): Promise|ResponseInterface
    {
        $request = $this->transport->createRequest(
            $endpoint->getMethod(),
            $endpoint->getURI(),
            $endpoint->getParams(),
            $endpoint->getBody(),
        );
        if ($this->isAsync()) {
            return $this->transport->sendAsyncRequest($request);
        }
        return $this->transport->sendRequest($request);
    }

}
