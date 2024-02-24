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

use Mockery as m;
use OpenSearch\Common\Exceptions\Curl\OperationTimeoutException;
use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\ConnectionPool\SniffingConnectionPool;
use OpenSearch\Connections\Connection;
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
        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            $connectionPoolParams
        );

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($mockConnection, $retConnection);
    }

    public function testItShouldDiscoverLiveConnections(): void
    {
        $downConnection = $this->createMock(Connection::class);
        $downConnection->method('isAlive')->willReturn(false);
        $downConnection->method('ping')->willReturn(false);
        $liveConnection = $this->createMock(Connection::class);
        $liveConnection->method('isAlive')->willReturn(true);
        $connections = [$downConnection, $liveConnection];
        $selector = $this->createMock(SelectorInterface::class);
        $selector->method('select')->willReturnOnConsecutiveCalls($downConnection, $liveConnection);
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls($downConnection, $liveConnection);

        $sut = new SniffingConnectionPool($connections, $selector, $connectionFactory, []);

        $this->assertSame($liveConnection, $sut->nextConnection());
    }

    public function testItShouldReturnTheFirstLiveConnection(): void
    {
        $firstConnection = $this->createMock(Connection::class);
        $firstConnection->method('isAlive')->willReturn(true);
        $secondConnection = $this->createMock(Connection::class);
        $secondConnection->method('isAlive')->willReturn(true);
        $connections = [$firstConnection, $secondConnection];
        $selector = $this->createMock(SelectorInterface::class);
        $selector->method('select')->willReturnOnConsecutiveCalls($firstConnection, $secondConnection);
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls($firstConnection, $secondConnection);

        $sut = new SniffingConnectionPool($connections, $selector, $connectionFactory, []);

        $this->assertSame($firstConnection, $sut->nextConnection());
    }

    public function testForcingNextConnectionSelection(): void
    {
        $clusterState = json_decode(
            file_get_contents(__DIR__.'/../fixtures/cluster-state.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $firstConnection = $this->createMock(Connection::class);
        $firstConnection->method('isAlive')->willReturn(true);
        $firstConnection->method('sniff')->willReturn($clusterState);
        $secondConnection = $this->createMock(Connection::class);
        $secondConnection->method('isAlive')->willReturn(true);
        $connections = [$firstConnection, $secondConnection];
        $selector = $this->createMock(SelectorInterface::class);
        $selector->method('select')->willReturnOnConsecutiveCalls($firstConnection, $secondConnection);
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls($firstConnection, $secondConnection);

        $sut = new SniffingConnectionPool($connections, $selector, $connectionFactory, []);

        $this->assertSame($secondConnection, $sut->nextConnection(true));
    }

    public function testAddOneHostAndForceNext(): void
    {
        $firstConnection = $this->createMock(Connection::class);
        $secondConnection = m::mock(Connection::class);
        $secondConnection->allows('isAlive')->andReturns(true);
        $selector = $this->createMock(SelectorInterface::class);
        $selector->method('select')->willReturnOnConsecutiveCalls($firstConnection, $secondConnection);
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturn($secondConnection);
        $connectionPool = new SniffingConnectionPool(
            [$firstConnection],
            $selector,
            $connectionFactory,
            ['randomizeHosts' => false]
        );

        $actualConnection = $connectionPool->nextConnection(true);

        $this->assertSame($secondConnection, $actualConnection);
    }

    public function testAddMultipleNodesThenGetConnection(): void
    {
        $connections = [];
        foreach (range(1, 10) as $ignored) {
            $mockConnection = $this->createMock(Connection::class);
            $mockConnection->method('ping')->willReturn(true);
            $mockConnection->method('isAlive')->willReturn(true);
            $connections[] = $mockConnection;
        }
        $selector = $this->createMock(SelectorInterface::class);
        $selector->method('select')->willReturn($connections[0]);
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionPoolParams = ['randomizeHosts' => false];
        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            $connectionPoolParams
        );

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($connections[0], $retConnection);
    }

    public function testAddMultipleNodesTimeoutAllButLast(): void
    {
        $connections = [];
        foreach (range(1, 10) as $ignored) {
            $mockConnection = $this->createMock(Connection::class);
            $mockConnection->method('ping')->willReturn(false);
            $mockConnection->method('isAlive')->willReturn(false);
            $connections[] = $mockConnection;
        }
        $liveConnection = $this->createMock(Connection::class);
        $liveConnection->method('ping')->willReturn(true);
        $liveConnection->method('isAlive')->willReturn(true);
        $connections[] = $liveConnection;
        $selector = $this->createMock(SelectorInterface::class);
        $selector->method('select')->willReturnOnConsecutiveCalls(...$connections);
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);

        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            ['randomizeHosts' => false]
        );

        $retConnection = $connectionPool->nextConnection();

        $this->assertSame($liveConnection, $retConnection);
    }

    public function testAddTenNodesAllTimeout(): void
    {
        $connections = [];
        foreach (range(1, 10) as $ignored) {
            $mockConnection = $this->createMock(Connection::class);
            $mockConnection->method('ping')->willReturn(false);
            $mockConnection->method('isAlive')->willReturn(false);
            $connections[] = $mockConnection;
        }

        $selector = $this->createMock(SelectorInterface::class);
        $selector->method('select')->willReturnCallback(
            function () use ($connections) {
                return array_shift($connections);
            }
        );

        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            ['randomizeHosts' => false]
        );

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $connectionPool->nextConnection();
    }

    public function testAddOneHostSniffTwo(): void
    {
        $clusterState = json_decode(
            file_get_contents(__DIR__.'/../fixtures/cluster-state.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
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
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns(
            $newConnections[0]
        );
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns(
            $newConnections[1]
        );

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval' => 0,
        ];
        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            $connectionPoolParams
        );

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[0], $retConnection);

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[1], $retConnection);
    }

    public function testAddSeedSniffTwoTimeoutTwo(): void
    {
        $clusterState = json_decode(
            '{"ok":true,"cluster_name":"opensearch","nodes":{"node1":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}, "node2":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9301]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9201]"}}}',
            true,
            512,
            JSON_THROW_ON_ERROR
        );

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
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns(
            $newConnections[0]
        );
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns(
            $newConnections[1]
        );

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval' => -1,
        ];
        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            $connectionPoolParams
        );

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $retConnection = $connectionPool->nextConnection();
    }

    public function testTenTimeoutNineSniffTenthAddTwoAlive(): void
    {
        $clusterState = json_decode(
            '{"ok":true,"cluster_name":"opensearch","nodes":{"node1":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}, "node2":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9301]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9201]"}}}',
            true,
            512,
            JSON_THROW_ON_ERROR
        );

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
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns(
            $newConnections[10]
        );
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns(
            $newConnections[11]
        );

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval' => -1,
        ];
        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            $connectionPoolParams
        );

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[11], $retConnection);

        $retConnection = $connectionPool->nextConnection();
        $this->assertSame($newConnections[12], $retConnection);
    }

    public function testTenTimeoutNineSniffTenthAddTwoDeadTimeoutEveryone(): void
    {
        $clusterState = json_decode(
            '{"ok":true,"cluster_name":"opensearch","nodes":{"node1":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9300]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9200]"}, "node2":{"name":"Vesta","transport_address":"inet[/192.168.1.119:9301]","hostname":"zach-ThinkPad-W530","version":"0.90.5","http_address":"inet[/192.168.1.119:9201]"}}}',
            true,
            512,
            JSON_THROW_ON_ERROR
        );

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
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9200])->andReturns(
            $newConnections[10]
        );
        $connectionFactory->allows('create')->with(['host' => '192.168.1.119', 'port' => 9201])->andReturns(
            $newConnections[11]
        );

        $connectionPoolParams = [
            'randomizeHosts' => false,
            'sniffingInterval' => -1,
        ];
        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            $connectionPoolParams
        );

        $this->expectException(NoNodesAvailableException::class);
        $this->expectExceptionMessage('No alive nodes found in your cluster');

        $retConnection = $connectionPool->nextConnection();
    }
}
