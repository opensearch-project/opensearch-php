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

namespace OpenSearch\Tests\ConnectionPool;

use OpenSearch;
use OpenSearch\ClientBuilder;
use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\ConnectionPool\Selectors\RoundRobinSelector;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\ConnectionPool\StaticConnectionPool;
use OpenSearch\Connections\Connection;
use OpenSearch\Connections\ConnectionFactory;
use Mockery as m;
use OpenSearch\Connections\ConnectionFactoryInterface;
use OpenSearch\Connections\ConnectionInterface;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(StaticConnectionPoolTest::class . ' is deprecated in 2.3.2 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * Class StaticConnectionPoolTest
 *
 * @subpackage Tests/StaticConnectionPoolTest
 * @group legacy
 *
 * @deprecated in 2.3.2 and will be removed in 3.0.0.
 */
class StaticConnectionPoolTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testAddOneHostThenGetConnection()
    {
        $mockConnection = m::mock(Connection::class);
        $mockConnection->expects('isAlive')->andReturns(true);
        $mockConnection->expects('markDead');

        /**
         * @var \OpenSearch\Connections\ConnectionInterface[]&\Mockery\MockInterface[] $connections
        */
        $connections = [$mockConnection];

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')
            ->andReturns($connections[0])
            ->getMock();

        $connectionFactory = m::mock(ConnectionFactory::class);

        $connectionPoolParams = [
            'randomizeHosts' => false,
        ];
        $connectionPool = new StaticConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($mockConnection, $retConnection);
    }

    public function testAddMultipleHostsThenGetFirst()
    {
        $connections = [];

        foreach (range(1, 10) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->expects('isAlive')->between(0, 1)->andReturns(true);
            $mockConnection->expects('markDead')->once();

            $connections[] = $mockConnection;
        }

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')
            ->andReturns($connections[0]);

        $connectionFactory = m::mock(ConnectionFactory::class);

        $connectionPoolParams = [
            'randomizeHosts' => false,
        ];
        $connectionPool = new StaticConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($connections[0], $retConnection);
    }

    public function testAllHostsFailPing()
    {
        $connections = [];

        foreach (range(1, 10) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->expects('ping')->andReturns(false);
            $mockConnection->expects('isAlive')->andReturns(false);
            $mockConnection->expects('markDead');
            $mockConnection->expects('getPingFailures')->andReturns(0);
            $mockConnection->expects('getLastPing')->andReturns(time());

            $connections[] = $mockConnection;
        }

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues($connections);

        $connectionFactory = m::mock(ConnectionFactory::class);

        $connectionPoolParams = [
            'randomizeHosts' => false,
        ];
        $connectionPool = new StaticConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $connectionPool->nextConnection();
    }

    public function testAllExceptLastHostFailPingRevivesInSkip()
    {
        $connections = [];

        foreach (range(1, 9) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->expects('ping')->andReturns(false);
            $mockConnection->expects('isAlive')->andReturns(false);
            $mockConnection->expects('markDead');
            $mockConnection->expects('getPingFailures')->andReturns(0);
            $mockConnection->expects('getLastPing')->andReturns(time());

            $connections[] = $mockConnection;
        }

        $goodConnection = m::mock(Connection::class);
        $goodConnection->expects('ping')->andReturns(true);
        $goodConnection->expects('isAlive')->andReturns(false);
        $goodConnection->expects('markDead');
        $goodConnection->expects('getPingFailures')->andReturns(0);
        $goodConnection->expects('getLastPing')->andReturns(time());

        $connections[] = $goodConnection;

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues($connections);

        $connectionFactory = m::mock(ConnectionFactory::class);

        $connectionPoolParams = [
            'randomizeHosts' => false,
        ];
        $connectionPool = new StaticConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $ret = $connectionPool->nextConnection();
        $this->assertSame($goodConnection, $ret);
    }

    public function testAllExceptLastHostFailPingRevivesPreSkip()
    {
        $connections = [];

        foreach (range(1, 9) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->expects('ping')->between(0, 1)->andReturns(false);
            $mockConnection->expects('isAlive')->andReturns(false);
            $mockConnection->expects('markDead');
            $mockConnection->expects('getPingFailures')->andReturns(0);
            $mockConnection->expects('getLastPing')->andReturns(time());

            $connections[] = $mockConnection;
        }

        $goodConnection = m::mock(Connection::class);
        $goodConnection->expects('ping')->andReturns(true);
        $goodConnection->expects('isAlive')->andReturns(false);
        $goodConnection->expects('markDead');
        $goodConnection->expects('getPingFailures')->andReturns(0);
        $goodConnection->expects('getLastPing')->andReturns(time() - 10000);

        $connections[] = $goodConnection;

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues($connections);

        $connectionFactory = m::mock(ConnectionFactory::class);

        $connectionPoolParams = [
            'randomizeHosts' => false,
        ];
        $connectionPool = new StaticConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $ret = $connectionPool->nextConnection();
        $this->assertSame($goodConnection, $ret);
    }

    public function testCustomConnectionPoolIT()
    {
        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts(['localhost:1']);
        $client = $clientBuilder
            ->setRetries(0)
            ->setConnectionPool(StaticConnectionPool::class, [])
            ->build();

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $client->search([]);
    }
}
