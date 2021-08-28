<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */

namespace OpenSearch\ConnectionPool;

use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\Connections\Connection;
use OpenSearch\Connections\ConnectionInterface;
use OpenSearch\Connections\ConnectionFactoryInterface;

class StaticConnectionPool extends AbstractConnectionPool implements ConnectionPoolInterface
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
     * {@inheritdoc}
     */
    public function __construct($connections, SelectorInterface $selector, ConnectionFactoryInterface $factory, $connectionPoolParams)
    {
        parent::__construct($connections, $selector, $factory, $connectionPoolParams);
        $this->scheduleCheck();
    }

    public function nextConnection(bool $force = false): ConnectionInterface
    {
        $skipped = [];

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
                if ($connection->ping() === true) {
                    return $connection;
                }
            } else {
                $skipped[] = $connection;
            }
        }

        // All "alive" nodes failed, force pings on "dead" nodes
        foreach ($skipped as $connection) {
            if ($connection->ping() === true) {
                return $connection;
            }
        }

        throw new NoNodesAvailableException("No alive nodes found in your cluster");
    }

    public function scheduleCheck(): void
    {
        foreach ($this->connections as $connection) {
            $connection->markDead();
        }
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
