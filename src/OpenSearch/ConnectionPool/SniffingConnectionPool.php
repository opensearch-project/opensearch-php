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

use OpenSearch\Common\Exceptions\Curl\OperationTimeoutException;
use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\Connections\Connection;
use OpenSearch\Connections\ConnectionInterface;
use OpenSearch\Connections\ConnectionFactoryInterface;

class SniffingConnectionPool extends AbstractConnectionPool implements ConnectionPoolInterface
{
    /**
     * @var int
     */
    private $sniffingInterval = 300;

    /**
     * @var int
     */
    private $nextSniff = -1;

    /**
     * {@inheritdoc}
     */
    public function __construct($connections, SelectorInterface $selector, ConnectionFactoryInterface $factory, $connectionPoolParams)
    {
        parent::__construct($connections, $selector, $factory, $connectionPoolParams);

        $this->setConnectionPoolParams($connectionPoolParams);
        $this->nextSniff = time() + $this->sniffingInterval;
    }

    public function nextConnection(bool $force = false): ConnectionInterface
    {
        $this->sniff($force);

        $size = count($this->connections);
        while ($size--) {
            /**
 * @var Connection $connection
*/
            $connection = $this->selector->select($this->connections);
            if ($connection->isAlive() === true || $connection->ping() === true) {
                return $connection;
            }
        }

        if ($force === true) {
            throw new NoNodesAvailableException("No alive nodes found in your cluster");
        }

        return $this->nextConnection(true);
    }

    public function scheduleCheck(): void
    {
        $this->nextSniff = -1;
    }

    private function sniff(bool $force = false)
    {
        if ($force === false && $this->nextSniff >= time()) {
            return;
        }

        $total = count($this->connections);

        while ($total--) {
            /**
 * @var Connection $connection
*/
            $connection = $this->selector->select($this->connections);

            if ($connection->isAlive() xor $force) {
                continue;
            }

            if ($this->sniffConnection($connection) === true) {
                return;
            }
        }

        if ($force === true) {
            return;
        }

        foreach ($this->seedConnections as $connection) {
            if ($this->sniffConnection($connection) === true) {
                return;
            }
        }
    }

    private function sniffConnection(Connection $connection): bool
    {
        try {
            $response = $connection->sniff();
        } catch (OperationTimeoutException $exception) {
            return false;
        }

        $nodes = $this->parseClusterState($connection->getTransportSchema(), $response);

        if (count($nodes) === 0) {
            return false;
        }

        $this->connections = array();

        foreach ($nodes as $node) {
            $nodeDetails = array(
                'host' => $node['host'],
                'port' => $node['port']
            );
            $this->connections[] = $this->connectionFactory->create($nodeDetails);
        }

        $this->nextSniff = time() + $this->sniffingInterval;

        return true;
    }

    private function parseClusterState(string $transportSchema, $nodeInfo): array
    {
        $pattern       = '/([^:]*):([0-9]+)/';
        $schemaAddress = $transportSchema . '_address';
        $hosts         = [];

        foreach ($nodeInfo['nodes'] as $node) {
            if (isset($node['http']) === true && isset($node['http']['publish_address']) === true) {
                if (preg_match($pattern, $node['http']['publish_address'], $match) === 1) {
                    $hosts[] = array(
                        'host' => $match[1],
                        'port' => (int) $match[2],
                    );
                }
            }
        }

        return $hosts;
    }

    private function setConnectionPoolParams(array $connectionPoolParams)
    {
        if (isset($connectionPoolParams['sniffingInterval']) === true) {
            $this->sniffingInterval = $connectionPoolParams['sniffingInterval'];
        }
    }
}
