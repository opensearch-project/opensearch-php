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

namespace OpenSearch\Tests\ConnectionPool;

use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\ConnectionPool\SniffingConnectionPool;
use OpenSearch\Connections\Connection;
use Mockery as m;
use OpenSearch\Common\Exceptions\Curl\OperationTimeoutException;
use OpenSearch\Connections\ConnectionFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class SniffingConnectionPoolTest
 *
 * @subpackage Tests/SniffingConnectionPoolTest
 */
class SniffingConnectionPoolTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testAddOneHostThenGetConnection(): void
    {
        $mockConnection = m::mock(Connection::class);
        $mockConnection->allows('ping')->andReturns(true);
        $mockConnection->allows('isAlive')->andReturns(true);

        $connections = [$mockConnection];

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturns($connections[0]);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);

        $connectionPoolParams = ['randomizeHosts' => false];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($mockConnection, $retConnection);
    }

    public function testAddOneHostAndTriggerSniff(): void
    {
        $clusterState = json_decode('{"ok":true,"cluster_name":"opensearch","nodes":{"Bl2ihSr7TcuUHxhu1GA_YQ":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}}}', true);

        $mockConnection = m::mock(Connection::class);
        $mockConnection->expects('ping')->andReturns(true);
        $mockConnection->expects('isAlive')->andReturns(true);
        $mockConnection->expects('getTransportSchema')->andReturns('http');
        $mockConnection->expects('sniff')->andReturns($clusterState);

        $connections = [$mockConnection];
        $mockNewConnection = m::mock(Connection::class);
        $mockNewConnection->allows('isAlive')->andReturns(true);

        $selector = m::mock(SelectorInterface::class);
        $selector->expects('select')->twice()->andReturns($mockNewConnection);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns($mockNewConnection);

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval'  => -1
        ];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($mockNewConnection, $retConnection);
    }

    public function testAddOneHostAndForceNext(): void
    {
        $clusterState = json_decode('{"ok":true,"cluster_name":"opensearch","nodes":{"Bl2ihSr7TcuUHxhu1GA_YQ":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}}}', true);

        $mockConnection = m::mock(Connection::class);
        $mockConnection->expects('ping')->andReturns(true);
        $mockConnection->expects('isAlive')->andReturns(true);
        $mockConnection->expects('getTransportSchema')->andReturns('http');
        $mockConnection->expects('sniff')->andReturns($clusterState);

        $connections = [$mockConnection];
        $mockNewConnection = m::mock(Connection::class);
        $mockNewConnection->allows('isAlive')->andReturns(true);

        $selector = m::mock(SelectorInterface::class);
        $selector->expects('select')->andReturns($mockConnection);
        $selector->expects('select')->andReturns($mockNewConnection);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns($mockNewConnection);

        $connectionPoolParams = [
            'randomizeHosts' => false
        ];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection(true);

        $this->assertSame($mockNewConnection, $retConnection);
    }

    public function testAddTenNodesThenGetConnection(): void
    {
        $connections = [];

        foreach (range(1, 10) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->allows('ping')->andReturns(true);
            $mockConnection->allows('isAlive')->andReturns(true);

            $connections[] = $mockConnection;
        }

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturns($connections[0]);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);

        $connectionPoolParams = ['randomizeHosts' => false];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($connections[0], $retConnection);
    }

    public function testAddTenNodesTimeoutAllButLast(): void
    {
        $connections = [];

        foreach (range(1, 9) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->allows('ping')->andReturns(false);
            $mockConnection->allows('isAlive')->andReturns(false);

            $connections[] = $mockConnection;
        }

        $mockConnection = m::mock(Connection::class);
        $mockConnection->allows('ping')->andReturns(true);
        $mockConnection->allows('isAlive')->andReturns(true);

        $connections[] = $mockConnection;

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues($connections);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);

        $connectionPoolParams = ['randomizeHosts' => false];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($connections[9], $retConnection);
    }

    public function testAddTenNodesAllTimeout(): void
    {
        $connections = [];

        foreach (range(1, 10) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->allows('ping')->andReturns(false);
            $mockConnection->allows('isAlive')->andReturns(false);

            $connections[] = $mockConnection;
        }

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues($connections);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);

        $connectionPoolParams = ['randomizeHosts' => false];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $connectionPool->nextConnection();
    }

    public function testAddOneHostSniffTwo(): void
    {
        $clusterState = json_decode('{"ok":true,"cluster_name":"opensearch","nodes":{"node1":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}, "node2":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9301]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9201]"}}}', true);

        $mockConnection = m::mock(Connection::class);
        $mockConnection->expects('ping')->andReturns(true);
        $mockConnection->expects('isAlive')->andReturns(true);
        $mockConnection->expects('getTransportSchema')->twice()->andReturns('http');
        $mockConnection->expects('sniff')->twice()->andReturns($clusterState);

        $connections = [$mockConnection];

        $newConnections = [];
        $newConnection = m::mock(Connection::class);
        $newConnection->allows('isAlive')->andReturns(true);

        $newConnections[] = $newConnection;
        $newConnections[] = $newConnection;

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues([
            //selects provided node first, then the new cluster list
            $mockConnection,
            $newConnections[0],
            $newConnections[1],
        ]);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns($newConnections[0]);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns($newConnections[1]);

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval'  => -1
        ];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[0], $retConnection);

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[1], $retConnection);
    }

    public function testAddSeedSniffTwoTimeoutTwo(): void
    {
        $clusterState = json_decode('{"ok":true,"cluster_name":"opensearch","nodes":{"node1":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}, "node2":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9301]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9201]"}}}', true);

        $mockConnection = m::mock(Connection::class);
        $mockConnection->expects('ping')->andReturns(true);
        $mockConnection->expects('isAlive')->andReturns(true);
        $mockConnection->expects('getTransportSchema')->andReturns('http');
        $mockConnection->expects('sniff')->andReturns($clusterState);

        $connections = [$mockConnection];

        $newConnections = [];
        $newConnection = m::mock(Connection::class);
        $newConnection->allows('isAlive')->andReturns(false);
        $newConnection->allows('ping')->andReturns(false);

        $newConnections[] = $newConnection;
        $newConnections[] = $newConnection;

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues([        //selects provided node first, then the new cluster list
            $mockConnection,
            $newConnections[0],
            $newConnections[1],
        ]);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns($newConnections[0]);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns($newConnections[1]);

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval'  => -1
        ];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $retConnection = $connectionPool->nextConnection();
    }

    public function testTenTimeoutNineSniffTenthAddTwoAlive(): void
    {
        $clusterState = json_decode('{"ok":true,"cluster_name":"opensearch","nodes":{"node1":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}, "node2":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9301]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9201]"}}}', true);

        $connections = [];

        foreach (range(1, 10) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->allows('ping')->andReturns(false);
            $mockConnection->allows('isAlive')->andReturns(true);
            $mockConnection->allows('sniff')->andThrow(OperationTimeoutException::class);

            $connections[] = $mockConnection;
        }

        $mockConnection = m::mock(Connection::class);
        $mockConnection->expects('ping')->andReturns(true);
        $mockConnection->expects('isAlive')->andReturns(true);
        $mockConnection->expects('sniff')->andReturns($clusterState);
        $mockConnection->expects('getTransportSchema')->twice()->andReturns('http');

        $connections[] = $mockConnection;

        $newConnections = $connections;
        $newConnection = m::mock(Connection::class);
        $newConnection->allows('isAlive')->andReturns(true);
        $newConnection->allows('ping')->andReturns(true);

        $newConnections[] = $newConnection;
        $newConnections[] = $newConnection;

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues($newConnections);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns($newConnections[10]);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns($newConnections[11]);

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval'  => -1
        ];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[11], $retConnection);

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[12], $retConnection);
    }

    public function testTenTimeoutNineSniffTenthAddTwoDeadTimeoutEveryone(): void
    {
        $clusterState = json_decode('{"ok":true,"cluster_name":"opensearch","nodes":{"node1":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}, "node2":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9301]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9201]"}}}', true);

        $connections = [];

        foreach (range(1, 10) as $index) {
            $mockConnection = m::mock(Connection::class);
            $mockConnection->allows('ping')->andReturns(false);
            $mockConnection->allows('isAlive')->andReturns(true);
            $mockConnection->allows('sniff')->andThrow(OperationTimeoutException::class);

            $connections[] = $mockConnection;
        }

        $mockConnection = m::mock(Connection::class);
        $mockConnection->expects('ping')->andReturns(true);
        $mockConnection->expects('isAlive')->andReturns(true);
        $mockConnection->expects('sniff')->andReturns($clusterState);
        $mockConnection->expects('getTransportSchema')->andReturns('http');
        $mockConnection->expects('sniff')->andThrow(OperationTimeoutException::class);

        $connections[] = $mockConnection;

        $newConnections = $connections;

        $newConnection = m::mock(Connection::class);
        $newConnection->allows('isAlive')->andReturns(false);
        $newConnection->allows('ping')->andReturns(false);
        $newConnection->allows('sniff')->andThrow(OperationTimeoutException::class);

        $newConnections[] = $newConnection;
        $newConnections[] = $newConnection;

        $selector = m::mock(SelectorInterface::class);
        $selector->allows('select')->andReturnValues($newConnections);

        $connectionFactory = m::mock(ConnectionFactoryInterface::class);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns($newConnections[10]);
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns($newConnections[11]);

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval'  => -1
        ];
        $connectionPool = new SniffingConnectionPool($connections, $selector, $connectionFactory, $connectionPoolParams);

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $retConnection = $connectionPool->nextConnection();
    }
}
