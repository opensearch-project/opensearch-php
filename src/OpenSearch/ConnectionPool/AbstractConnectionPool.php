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

use OpenSearch\Common\Exceptions\InvalidArgumentException;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\Connections\ConnectionFactoryInterface;
use OpenSearch\Connections\ConnectionInterface;

abstract class AbstractConnectionPool implements ConnectionPoolInterface
{
    /**
     * Array of connections
     *
     * @var ConnectionInterface[]
     */
    protected $connections;

    /**
     * Array of initial seed connections
     *
     * @var ConnectionInterface[]
     */
    protected $seedConnections;

    /**
     * Selector object, used to select a connection on each request
     *
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @var array
     */
    protected $connectionPoolParams;

    /**
     * @var \OpenSearch\Connections\ConnectionFactory
     */
    protected $connectionFactory;

    /**
     * Constructor
     *
     * @param ConnectionInterface[]      $connections          The Connections to choose from
     * @param SelectorInterface          $selector             A Selector instance to perform the selection logic for the available connections
     * @param ConnectionFactoryInterface $factory              ConnectionFactory instance
     * @param array                      $connectionPoolParams
     */
    public function __construct(array $connections, SelectorInterface $selector, ConnectionFactoryInterface $factory, array $connectionPoolParams)
    {
        $paramList = array('connections', 'selector', 'connectionPoolParams');
        foreach ($paramList as $param) {
            if (isset($$param) === false) {
                throw new InvalidArgumentException('`' . $param . '` parameter must not be null');
            }
        }

        if (isset($connectionPoolParams['randomizeHosts']) === true
            && $connectionPoolParams['randomizeHosts'] === true
        ) {
            shuffle($connections);
        }

        $this->connections          = $connections;
        $this->seedConnections      = $connections;
        $this->selector             = $selector;
        $this->connectionPoolParams = $connectionPoolParams;
        $this->connectionFactory    = $factory;
    }

    abstract public function nextConnection(bool $force = false): ConnectionInterface;

    abstract public function scheduleCheck(): void;
}
