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

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Http\Promise\Promise;
use OpenSearch\RequestFactoryInterface;
use OpenSearch\Transport;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class TransportTest
 *
 * @coversDefaultClass \OpenSearch\Transport
 */
class TransportTest extends TestCase
{
    /**
     * The transport instance under test.
     */
    private Transport $transport;

    public function setUp(): void
    {
        parent::setUp();

        $mockHandler = new MockHandler([
            new Response(200, ["content-type" => "text/javascript; charset=utf-8"], '{"foo": "bar"}'),
            new RequestException('Error Communicating with Server', $this->createMock(RequestInterface::class)),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $httpClient = new GuzzleAdapter(new GuzzleClient(['handler' => $handlerStack]));

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn($this->createMock(RequestInterface::class));

        $this->transport = new Transport($httpClient, $requestFactory);
    }

    /**
     * @covers ::sendRequest
     */
    public function testSendRequest(): void
    {
        $request = new Request('GET', 'http://localhost:9200');
        $response = $this->transport->sendRequest($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/javascript; charset=utf-8', $response->getHeaderLine('content-type'));
        $this->assertEquals('{"foo": "bar"}', $response->getBody()->getContents());

        $this->expectException(RequestExceptionInterface::class);
        $this->expectExceptionMessage('Error Communicating with Server');
        $this->transport->sendRequest($request);
    }

    /**
     * @covers ::sendAsyncRequest
     */
    public function testSendAsyncRequest(): void
    {
        $request = new Request('GET', 'http://localhost:9200');
        $promise = $this->transport->sendAsyncRequest($request);
        $this->assertInstanceOf(Promise::class, $promise);
        $response = $promise->wait();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/javascript; charset=utf-8', $response->getHeaderLine('content-type'));
        $this->assertEquals('{"foo": "bar"}', $response->getBody()->getContents());

        $promise = $this->transport->sendAsyncRequest($request);
        $this->assertInstanceOf(Promise::class, $promise);
        $this->expectException(RequestExceptionInterface::class);
        $this->expectExceptionMessage('Error Communicating with Server');
        $promise->wait();
    }

}
