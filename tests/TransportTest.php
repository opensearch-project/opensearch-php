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

namespace OpenSearch\Tests;

use OpenSearch\Common\Exceptions\ServerErrorResponseException;
use OpenSearch\ConnectionPool\AbstractConnectionPool;
use OpenSearch\Connections\Connection;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Transport;
use GuzzleHttp\Ring\Future\FutureArray;
use GuzzleHttp\Ring\Future\FutureArrayInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use React\Promise\Deferred;
use React\Promise\Promise;

class TransportTest extends TestCase
{
    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->trace = $this->createMock(LoggerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->connectionPool = $this->createMock(AbstractConnectionPool::class);
        $this->connection = $this->createMock(Connection::class);
    }

    public function testPerformRequestWithServerErrorResponseException404Result()
    {
        $deferred = new Deferred();
        $deferred->reject(new ServerErrorResponseException('foo', 404));
        $future = new FutureArray($deferred->promise());

        $this->connection->method('performRequest')
            ->willReturn($future);

        $this->connectionPool->method('nextConnection')
            ->willReturn($this->connection);

        $this->connectionPool->expects($this->never())
            ->method('scheduleCheck');

        $transport = new Transport(1, $this->connectionPool, $this->logger);

        $result = $transport->performRequest('GET', '/');
        $this->assertInstanceOf(FutureArrayInterface::class, $result);
    }

    public function testPerformRequestWithServerErrorResponseException500Result()
    {
        $deferred = new Deferred();
        $deferred->reject(new ServerErrorResponseException('foo', 500));
        $future = new FutureArray($deferred->promise());

        $this->connection->method('performRequest')
            ->willReturn($future);

        $this->connectionPool->method('nextConnection')
            ->willReturn($this->connection);

        $this->connectionPool->expects($this->once())
            ->method('scheduleCheck');

        $transport = new Transport(1, $this->connectionPool, $this->logger);

        $result = $transport->performRequest('GET', '/');
        $this->assertInstanceOf(FutureArrayInterface::class, $result);
    }
}
