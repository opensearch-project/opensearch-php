<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file to be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */

namespace OpenSearch\Tests\ConnectionPool;

use OpenSearch\Common\Exceptions\Curl\OperationTimeoutException;
use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\ConnectionPool\Selectors\RoundRobinSelector;
use OpenSearch\ConnectionPool\SniffingConnectionPool;
use OpenSearch\Connections\Connection;
use OpenSearch\Connections\ConnectionFactoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(SniffingConnectionPoolTest::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * Class SniffingConnectionPoolTest
 *
 * @subpackage Tests/SniffingConnectionPoolTest
 *
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 */
class SniffingConnectionPoolTest extends TestCase
{
    #[Test]
    public function itShouldReturnTheSingleLiveConnectionAvailable(): void
    {
        $clusterState = $this->clusterState(1);
        $connection = $this->createMock(Connection::class);
        $connection->method('isAlive')->willReturn(true);
        $connection->method('sniff')->willReturn($clusterState);
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturn($connection);

        $connectionPool = new SniffingConnectionPool(
            [$connection],
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $this->assertSame($connection, $connectionPool->nextConnection());
    }

    #[Test]
    public function itShouldSniffNewConnectionsWhenPossible(): void
    {
        $clusterState = $this->clusterState(2);
        $originalConnection = $this->createMock(Connection::class);
        $originalConnection->method('isAlive')->willReturn(false);
        $originalConnection->method('sniff')->willReturn($clusterState);
        $discoveredConnection = $this->createMock(Connection::class);
        $discoveredConnection->method('isAlive')->willReturn(true);
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls($originalConnection, $discoveredConnection);

        $connectionPool = new SniffingConnectionPool(
            [$originalConnection],
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $actualConnection = $connectionPool->nextConnection();

        $this->assertSame($discoveredConnection, $actualConnection);
    }

    #[Test]
    public function forceNextConnection(): void
    {
        $clusterState = $this->clusterState(2);
        $firstConnection = $this->createMock(Connection::class);
        $firstConnection->method('isAlive')->willReturn(true);
        $firstConnection->method('sniff')->willReturn($clusterState);
        $secondConnection = $this->createMock(Connection::class);
        $secondConnection->method('isAlive')->willReturn(true);
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls($firstConnection, $secondConnection);

        $connectionPool = new SniffingConnectionPool(
            [$firstConnection, $secondConnection],
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $this->assertSame($secondConnection, $connectionPool->nextConnection(true));
    }

    #[Test]
    public function itShouldReturnFirstSeededConnectionIfAlive(): void
    {
        $clusterState = $this->clusterState(10);
        $connections = [];
        for ($i = 1; $i <= 10; $i++) {
            $connection = $this->createMock(Connection::class);
            $connection->method('isAlive')->willReturn(true);
            $connection->method('sniff')->willReturn($clusterState);
            $connections[] = $connection;
        }
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls(...$connections);

        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $this->assertSame($connections[0], $connectionPool->nextConnection());
    }

    #[Test]
    public function itShouldReturnTheFirstAvailableConnection(): void
    {
        $clusterState = $this->clusterState(10);
        $connections = [];
        for ($i = 1; $i <= 10; $i++) {
            $connection = $this->createMock(Connection::class);
            $connection->method('isAlive')->willReturn(false);
            $connection->method('sniff')->willReturn($clusterState);
            $connections[] = $connection;
        }
        $randomLiveConnectionIndex = random_int(0, 9);
        $connections[$randomLiveConnectionIndex] = $this->createMock(Connection::class);
        $connections[$randomLiveConnectionIndex]->method('isAlive')->willReturn(true);
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls(...$connections);

        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $this->assertSame($connections[$randomLiveConnectionIndex], $connectionPool->nextConnection());
    }

    #[Test]
    public function itShouldFailIfAllNodesAreDown(): void
    {
        $clusterState = $this->clusterState(10);
        $connections = [];
        for ($i = 1; $i <= 10; $i++) {
            $connection = $this->createMock(Connection::class);
            $connection->method('isAlive')->willReturn(false);
            $connection->method('sniff')->willReturn($clusterState);
            $connections[] = $connection;
        }
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls(...$connections);

        $connectionPool = new SniffingConnectionPool(
            $connections,
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $this->expectException(NoNodesAvailableException::class);

        $connectionPool->nextConnection();
    }

    #[Test]
    public function sniffShouldStopIfAllSniffRequestsFail(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection->method('isAlive')->willReturn(false);
        $connection->method('sniff')->willThrowException(new OperationTimeoutException());
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);

        $connectionPool = new SniffingConnectionPool(
            [$connection],
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $this->expectException(NoNodesAvailableException::class);
        $connectionFactory->expects($this->never())->method('create');

        $connectionPool->nextConnection();
    }

    #[Test]
    public function sniffShouldStopIfNodesAreEmpty(): void
    {
        $clusterState = $this->clusterState(0);
        $connection = $this->createMock(Connection::class);
        $connection->method('isAlive')->willReturn(false);
        $connection->method('sniff')->willReturn($clusterState);
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);

        $connectionPool = new SniffingConnectionPool(
            [$connection],
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 0]
        );

        $this->expectException(NoNodesAvailableException::class);
        $connectionFactory->expects($this->never())->method('create');

        $connectionPool->nextConnection();
    }

    #[Test]
    public function itShouldNotSniffBeforeScheduledSniffTime(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection->method('isAlive')->willReturn(false);
        $connection->method('sniff')->willReturn($this->clusterState(2));
        $selector = new RoundRobinSelector();
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);

        $connectionPool = new SniffingConnectionPool(
            [$connection],
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 300]
        );

        $connectionFactory->expects($this->never())->method('create');
        $this->expectException(NoNodesAvailableException::class);

        $connectionPool->nextConnection();
    }

    #[Test]
    public function scheduleCheck(): void
    {
        $clusterState = $this->clusterState(2);
        $firstConnection = $this->createMock(Connection::class);
        $firstConnection->method('isAlive')->willReturn(true);
        $firstConnection->method('sniff')->willReturn($clusterState);
        $secondConnection = $this->createMock(Connection::class);
        $secondConnection->method('isAlive')->willReturn(true);
        $selector = $this->createMock(RoundRobinSelector::class);
        $selector->expects($this->exactly(2))->method('select')->willReturnOnConsecutiveCalls(
            $firstConnection,
            $secondConnection
        );
        $connectionFactory = $this->createMock(ConnectionFactoryInterface::class);
        $connectionFactory->method('create')->willReturnOnConsecutiveCalls($firstConnection, $secondConnection);

        $connectionPool = new SniffingConnectionPool(
            [$firstConnection],
            $selector,
            $connectionFactory,
            ['sniffingInterval' => 300]
        );

        $connectionPool->scheduleCheck();

        $this->assertSame($secondConnection, $connectionPool->nextConnection());
    }

    private function clusterState(int $numberOfNodes): array
    {
        $clusterState = ['nodes' => []];

        for ($i = 1; $i <= $numberOfNodes; $i++) {
            $clusterState['nodes']["node-$i"] = [
                'http' => [
                    'publish_address' => "172.17.0.2:920$i",
                ],
            ];
        }

        return $clusterState;
    }
}
