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

namespace OpenSearch\ConnectionPool;

use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\Connections\Connection;
use OpenSearch\Connections\ConnectionFactoryInterface;
use OpenSearch\Connections\ConnectionInterface;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(StaticNoPingConnectionPool::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 *
 * @phpstan-ignore class.extendsDeprecatedClass
 */
class StaticNoPingConnectionPool extends AbstractConnectionPool implements ConnectionPoolInterface
{
    /**
     * @var int
     */
    private $pingTimeout    = 60;

    /**
     * @var int
     */
    private $maxPingTimeout = 3600;

    /**
     * @param ConnectionInterface[] $connections
     * @param array<string, mixed>  $connectionPoolParams
     */
    public function __construct($connections, SelectorInterface $selector, ConnectionFactoryInterface $factory, $connectionPoolParams)
    {
        parent::__construct($connections, $selector, $factory, $connectionPoolParams);
    }

    public function nextConnection(bool $force = false): ConnectionInterface
    {
        $total = count($this->connections);
        while ($total--) {
            /**
 * @var Connection $connection
*/
            $connection = $this->selector->select($this->connections);
            if ($connection->isAlive() === true) {
                return $connection;
            }

            if ($this->readyToRevive($connection) === true) {
                return $connection;
            }
        }

        throw new NoNodesAvailableException("No alive nodes found in your cluster");
    }

    public function scheduleCheck(): void
    {
    }

    private function readyToRevive(Connection $connection): bool
    {
        $timeout = min(
            $this->pingTimeout * pow(2, $connection->getPingFailures()),
            $this->maxPingTimeout
        );

        if ($connection->getLastPing() + $timeout < time()) {
            return true;
        } else {
            return false;
        }
    }
}
