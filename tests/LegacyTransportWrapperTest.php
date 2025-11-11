<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use GuzzleHttp\Ring\Future\FutureArray;
use OpenSearch\ConnectionPool\AbstractConnectionPool;
use OpenSearch\Connections\Connection;
use OpenSearch\LegacyTransportWrapper;
use OpenSearch\Transport;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use React\Promise\Deferred;

/**
 * Tests for the LegacyTransportWrapper class.
 *
 * @deprecated in 2.4.2 and will be removed in 3.0.0.
 */
#[Group('legacy')]
#[CoversClass(LegacyTransportWrapper::class)]
class LegacyTransportWrapperTest extends TestCase
{
    private Connection&MockObject $connection;

    private AbstractConnectionPool&MockObject $connectionPool;

    private MockObject&LoggerInterface $logger;

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->connectionPool = $this->createMock(AbstractConnectionPool::class);
        $this->connection = $this->createMock(Connection::class);
    }

    public function testSuccess(): void
    {
        $deferred = new Deferred();
        $deferred->resolve(['foo' => 'bar']);
        $future = new FutureArray($deferred->promise());

        $this->connection->method('performRequest')
            ->willReturn($future);

        $this->connectionPool->method('nextConnection')
            ->willReturn($this->connection);

        $transport = new Transport(1, $this->connectionPool, $this->logger);

        $wrapper = new LegacyTransportWrapper($transport);

        $response = $wrapper->sendRequest('GET', 'http://localhost:9200', [], null, []);

        $this->assertIsIterable($response);

        $this->assertEquals(['foo' => 'bar'], $response);
    }

}
