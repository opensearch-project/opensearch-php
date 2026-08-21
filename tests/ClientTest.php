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

namespace OpenSearch\Tests;

use OpenSearch\Client;
use OpenSearch\EndpointFactoryInterface;
use OpenSearch\Endpoints\Delete;
use OpenSearch\Exception\RuntimeException;
use OpenSearch\TransportInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests the client.
 */
#[CoversClass(Client::class)]
class ClientTest extends TestCase
{
    /**
     * The client under test.
     */
    private Client $client;

    private EndpointFactoryInterface|MockObject $endpointFactory;

    private TransportInterface|MockObject $transport;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->transport = $this->createMock(TransportInterface::class);
        $this->endpointFactory = $this->createMock(EndpointFactoryInterface::class);
        $registeredNamespaces = [];
        $this->client = new Client($this->transport, $this->endpointFactory, $registeredNamespaces);
    }

    public function testUnknownNamespace(): void
    {
        $this->expectException(\BadMethodCallException::class);
        // @phpstan-ignore method.notFound
        $this->client->foo();
    }

    public function testIndexCannotBeNullForDelete()
    {
        $this->endpointFactory->expects($this->once())
            ->method('getEndpoint')
            ->with(Delete::class)
            ->willReturn(new Delete());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('index is required for delete');

        $this->client->delete(
            [
                'index' => null,
                'id' => 'test'
            ]
        );
    }

    public function testIdCannotBeNullForDelete()
    {
        $this->endpointFactory->expects($this->once())
            ->method('getEndpoint')
            ->with(Delete::class)
            ->willReturn(new Delete());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('id is required for delete');

        $this->client->delete(
            [
                'index' => 'test',
                'id' => null
            ]
        );
    }

    public function testSendRawRequest(): void
    {
        $this->transport->expects($this->once())
            ->method('sendRequest')
            ->with('GET', '/', ['foo' => 'bar'], 'whizz')
            ->willReturn(['bang']);

        $response = $this->client->request('GET', '/', [
            'params' => ['foo' => 'bar'],
            'body' => 'whizz',
        ]);

        $this->assertEquals(['bang'], $response);
    }

    public function testOptionalEndpointFactory(): void
    {

        $this->transport->expects($this->once())
            ->method('sendRequest')
            ->with('GET', '/', ['foo' => 'bar'], 'whizz')
            ->willReturn(['bang']);

        $this->client = new Client($this->transport);

        $response = $this->client->request('GET', '/', [
            'params' => ['foo' => 'bar'],
            'body' => 'whizz',
        ]);

        $this->assertEquals(['bang'], $response);

    }

}
