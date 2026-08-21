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

namespace OpenSearch\Connections;

use OpenSearch\Serializers\SerializerInterface;
use Psr\Log\LoggerInterface;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(ConnectionFactory::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 */
class ConnectionFactory implements ConnectionFactoryInterface
{
    /**
     * @var array
     */
    private $connectionParams;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LoggerInterface
     */
    private $tracer;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @param array{client?: array{headers?: array<string, list<string>>, curl?: array<int, mixed>}} $connectionParams
     */
    public function __construct(callable $handler, array $connectionParams, SerializerInterface $serializer, LoggerInterface $logger, LoggerInterface $tracer)
    {
        $this->handler          = $handler;
        $this->connectionParams = $connectionParams;
        $this->logger           = $logger;
        $this->tracer           = $tracer;
        $this->serializer       = $serializer;
    }

    public function create(array $hostDetails): ConnectionInterface
    {
        if (isset($hostDetails['path'])) {
            $hostDetails['path'] = rtrim($hostDetails['path'], '/');
        }

        return new Connection(
            $this->handler,
            $hostDetails,
            $this->connectionParams,
            $this->serializer,
            $this->logger,
            $this->tracer
        );
    }
}
