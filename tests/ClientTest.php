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

use Mockery as m;
use OpenSearch;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use OpenSearch\Common\Exceptions\MaxRetriesException;

/**
 * Class ClientTest
 *
 * @subpackage Tests
 */
class ClientTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testFromConfig()
    {
        $params = [
            'hosts' => [
                'localhost:9200'
            ],
            'retries' => 2,
        ];
        $client = ClientBuilder::fromConfig($params);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testFromConfigBadParam()
    {
        $params = [
            'hosts' => [
                'localhost:9200'
            ],
            'retries' => 2,
            'imNotReal' => 5
        ];

        $this->expectException(\OpenSearch\Common\Exceptions\RuntimeException::class);
        $this->expectExceptionMessage('Unknown parameters provided: imNotReal');

        $client = ClientBuilder::fromConfig($params);
    }

    public function testFromConfigBadParamQuiet()
    {
        $params = [
            'hosts' => [
                'localhost:9200'
            ],
            'retries' => 2,
            'imNotReal' => 5
        ];
        $client = ClientBuilder::fromConfig($params, true);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testIndexCannotBeNullForDelete()
    {
        $client = ClientBuilder::create()->build();

        $this->expectException(OpenSearch\Common\Exceptions\RuntimeException::class);
        $this->expectExceptionMessage('index is required for delete');

        $client->delete(
            [
                'index' => null,
                'id' => 'test'
            ]
        );
    }

    public function testIdCannotBeNullForDelete()
    {
        $client = ClientBuilder::create()->build();

        $this->expectException(OpenSearch\Common\Exceptions\RuntimeException::class);
        $this->expectExceptionMessage('id is required for delete');

        $client->delete(
            [
                'index' => 'test',
                'id' => null
            ]
        );
    }

    public function testMaxRetriesException()
    {
        $client = OpenSearch\ClientBuilder::create()
            ->setHosts(["localhost:1"])
            ->setRetries(0)
            ->build();

        $searchParams = [
            'index' => 'test',
            'body' => [
                'query' => [
                    'match_all' => []
                ]
            ]
        ];

        $client = OpenSearch\ClientBuilder::create()
            ->setHosts(["localhost:1"])
            ->setRetries(0)
            ->build();

        try {
            $client->search($searchParams);
            $this->fail("Should have thrown CouldNotConnectToHost");
        } catch (OpenSearch\Common\Exceptions\Curl\CouldNotConnectToHost $e) {
            // All good
            $previous = $e->getPrevious();
            $this->assertInstanceOf(MaxRetriesException::class, $previous);
        } catch (\Exception $e) {
            throw $e;
        }


        $client = OpenSearch\ClientBuilder::create()
            ->setHosts(["localhost:1"])
            ->setRetries(0)
            ->build();

        try {
            $client->search($searchParams);
            $this->fail("Should have thrown TransportException");
        } catch (OpenSearch\Common\Exceptions\TransportException $e) {
            // All good
            $previous = $e->getPrevious();
            $this->assertInstanceOf(MaxRetriesException::class, $previous);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function testExtractArgumentIterable()
    {
        $client = OpenSearch\ClientBuilder::create()->build();
        // array iterator can be casted to array back, so make more real with IteratorIterator
        $body = new \IteratorIterator(new \ArrayIterator([1, 2, 3]));
        $params = ['body' => $body];
        $argument = $client->extractArgument($params, 'body');
        $this->assertEquals($body, $argument);
        $this->assertCount(0, $params);
        $this->assertInstanceOf(\IteratorIterator::class, $argument);
    }

    /** @test */
    public function sendRawRequest(): void
    {
        $callable = function () {};
        $transport = $this->createMock(OpenSearch\Transport::class);
        $client = new OpenSearch\Client($transport, $callable, []);

        $transport->expects($this->once())->method('performRequest')->with('GET', '/', [], null, []);

        $client->request('GET', '/');
    }

    /** @test */
    public function sendRawRequestWithBody(): void
    {
        $callable = function () {};
        $transport = $this->createMock(OpenSearch\Transport::class);
        $client = new OpenSearch\Client($transport, $callable, []);
        $body = ['query' => ['match' => ['text_entry' => 'long live king']]];

        $transport->expects($this->once())->method('performRequest')->with('GET', '/shakespeare/_search', [], $body, []);

        $client->request('GET', '/shakespeare/_search', compact('body'));
    }

    /** @test */
    public function sendRawRequestWithParams(): void
    {
        $callable = function () {};
        $transport = $this->createMock(OpenSearch\Transport::class);
        $client = new OpenSearch\Client($transport, $callable, []);
        $params = ['foo' => 'bar'];

        $transport->expects($this->once())->method('performRequest')->with('GET', '/_search', $params, null, []);

        $client->request('GET', '/_search', compact('params'));
    }

    /** @test */
    public function sendRawRequestWithOptions(): void
    {
        $callable = function () {};
        $transport = $this->createMock(OpenSearch\Transport::class);
        $client = new OpenSearch\Client($transport, $callable, []);
        $options = ['client' => ['future' => 'lazy']];

        $transport->expects($this->once())->method('performRequest')->with('GET', '/', [], null, $options);

        $client->request('GET', '/', compact('options'));
    }
}
