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

namespace OpenSearch\Tests\Connections;

use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use OpenSearch\Common\Exceptions\ServerErrorResponseException;
use OpenSearch\Connections\Connection;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Serializers\SmartSerializer;
use OpenSearch\Tests\ClientBuilder\ArrayLogger;
use Exception;
use Psr\Log\LoggerInterface;
use ReflectionClass;

use function base64_encode;
use function random_bytes;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(ConnectionTest::class . ' is deprecated in 2.3.2 and will be removed in 3.0.0.', E_USER_DEPRECATED);


/**
 * @covers \OpenSearch\Connections\Connection
 * @group legacy
 *
 * @deprecated in 2.3.2 and will be removed in 3.0.0.
 */
class ConnectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Psr\Log\LoggerInterface
     */
    private $trace;
    /**
     * @var \OpenSearch\Serializers\SerializerInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private $serializer;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->trace = $this->createMock(LoggerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    public function testConstructor()
    {
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            function () {
            },
            $host,
            [],
            $this->serializer,
            $this->logger,
            $this->trace
        );

        $this->assertInstanceOf(Connection::class, $connection);
    }

    /**
     * @depends testConstructor
     */
    public function testGetHeadersContainUserAgent()
    {
        $params = [];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            function () {
            },
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );

        $headers = $connection->getHeaders();

        $this->assertArrayHasKey('User-Agent', $headers);
        $this->assertStringContainsString('opensearch-php/'. Client::VERSION, $headers['User-Agent'][0]);
    }

    /**
     * @depends testGetHeadersContainUserAgent
     */
    public function testUserAgentHeaderIsSent()
    {
        $params = [];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey('User-Agent', $request['headers']);
        $this->assertStringContainsString('opensearch-php/'. Client::VERSION, $request['headers']['User-Agent'][0]);
    }

    /**
     * @depends testConstructor
     */
    public function testGetHeadersContainsHostArrayConfig()
    {
        $host = [
            'host' => 'localhost',
            'user' => 'foo',
            'pass' => 'bar',
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            [],
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey(CURLOPT_HTTPAUTH, $request['client']['curl']);
        $this->assertArrayHasKey(CURLOPT_USERPWD, $request['client']['curl']);
        $this->assertArrayNotHasKey('Authorization', $request['headers']);
        $this->assertStringContainsString('foo:bar', $request['client']['curl'][CURLOPT_USERPWD]);
    }

    /**
     * @depends testGetHeadersContainsHostArrayConfig
     */
    public function testGetHeadersContainApiKeyAuth()
    {
        $params = ['client' => ['headers' => [
            'Authorization' => [
                'ApiKey ' . base64_encode(random_bytes(10))
            ]
        ] ] ];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey('Authorization', $request['headers']);
        $this->assertArrayNotHasKey(CURLOPT_HTTPAUTH, $request['headers']);
        $this->assertStringContainsString($params['client']['headers']['Authorization'][0], $request['headers']['Authorization'][0]);
    }

    /**
     * @depends testGetHeadersContainApiKeyAuth
     */
    public function testGetHeadersContainApiKeyAuthOverHostArrayConfig()
    {
        $params = ['client' => ['headers' => [
            'Authorization' => [
                'ApiKey ' . base64_encode(random_bytes(10))
            ]
        ] ] ];
        $host = [
            'host' => 'localhost',
            'user' => 'foo',
            'pass' => 'bar',
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey('Authorization', $request['headers']);
        $this->assertArrayNotHasKey(CURLOPT_HTTPAUTH, $request['headers']);
        $this->assertStringContainsString($params['client']['headers']['Authorization'][0], $request['headers']['Authorization'][0]);
    }

    /**
     * @depends testGetHeadersContainsHostArrayConfig
     */
    public function testGetHeadersContainBasicAuth()
    {
        $params = ['client' => ['curl' => [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => 'username:password',
        ] ] ];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey(CURLOPT_HTTPAUTH, $request['client']['curl']);
        $this->assertArrayHasKey(CURLOPT_USERPWD, $request['client']['curl']);
        $this->assertArrayNotHasKey('Authorization', $request['headers']);
        $this->assertStringContainsString($params['client']['curl'][CURLOPT_USERPWD], $request['client']['curl'][CURLOPT_USERPWD]);
    }

    /**
     * @depends testGetHeadersContainBasicAuth
     */
    public function testGetHeadersContainBasicAuthOverHostArrayConfig()
    {
        $params = ['client' => ['curl' => [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => 'username:password',
        ] ] ];
        $host = [
            'host' => 'localhost',
            'user' => 'foo',
            'pass' => 'bar',
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey(CURLOPT_HTTPAUTH, $request['client']['curl']);
        $this->assertArrayHasKey(CURLOPT_USERPWD, $request['client']['curl']);
        $this->assertArrayNotHasKey('Authorization', $request['headers']);
        $this->assertStringContainsString('username:password', $request['client']['curl'][CURLOPT_USERPWD]);
    }

    /**
     * @see https://github.com/elastic/elasticsearch-php/issues/977
     */
    public function testTryDeserializeErrorWithMasterNotDiscoveredException()
    {
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            function () {
            },
            $host,
            [],
            new SmartSerializer(),
            $this->logger,
            $this->trace
        );

        $reflection = new ReflectionClass(Connection::class);
        $tryDeserializeError = $reflection->getMethod('tryDeserializeError');
        $tryDeserializeError->setAccessible(true);

        $body = '{"error":{"root_cause":[{"type":"master_not_discovered_exception","reason":null}],"type":"master_not_discovered_exception","reason":null},"status":503}';
        $response = [
            'transfer_stats' => [],
            'status' => 503,
            'body' => $body
        ];

        $result = $tryDeserializeError->invoke($connection, $response, ServerErrorResponseException::class);
        $this->assertInstanceOf(ServerErrorResponseException::class, $result);
        $this->assertStringContainsString('master_not_discovered_exception', $result->getMessage());
    }

    /**
     * @see https://github.com/opensearch-project/opensearch-php/issues/167
     */
    public function testTryDeserializeErrorWith403Error()
    {
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            function () {
            },
            $host,
            [],
            new SmartSerializer(),
            $this->logger,
            $this->trace
        );

        $reflection = new ReflectionClass(Connection::class);
        $tryDeserializeError = $reflection->getMethod('tryDeserializeError');
        $tryDeserializeError->setAccessible(true);

        $body = '{"status":403,"error":{"reason":"403 Forbidden","type":"Forbidden"}}';
        $response = [
            'transfer_stats' => [],
            'status' => 403,
            'body' => $body
        ];

        $result = $tryDeserializeError->invoke($connection, $response, ServerErrorResponseException::class);
        $this->assertInstanceOf(ServerErrorResponseException::class, $result);
        $this->assertStringContainsString('403 Forbidden', $result->getMessage());
    }

    public function testHeaderClientParamIsResetAfterSent()
    {
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            [],
            new SmartSerializer(),
            $this->logger,
            $this->trace
        );

        $options = [
            'client' => [
                'headers' => [
                    'Foo' => [ 'Bar' ]
                ]
            ]
        ];

        $headersBefore = $connection->getHeaders();
        $connection->performRequest('GET', '/', null, null, $options);
        $headersAfter = $connection->getHeaders();
        $this->assertEquals($headersBefore, $headersAfter);
    }

    public function testParametersAreSent()
    {
        $connectionParams = [];
        $host = [
            'host' => 'localhost'
        ];
        $requestParams = [
            'foo' => true,
            'baz' => false,
            'bar' => 'baz'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $connectionParams,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $connection->performRequest('GET', '/', $requestParams);
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertEquals('/?foo=true&baz=false&bar=baz', $request['uri']);
    }

    public function testPortInUrlWhenLogRequestSuccess()
    {
        $logger = new ArrayLogger();
        $trace = new ArrayLogger();

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            [
                'host'   => 'localhost',
                'port'   => 9200,
                'scheme' => 'http',
                'path'   => '/info'
            ],
            [],
            $this->serializer,
            $logger,
            $trace
        );
        $request = [
            'body' => '{}',
            'http_method' => 'GET',
            'headers' => [
                'User-Agent: Testing'
            ]
        ];
        $response = [
            'effective_url' => 'http://localhost/info',
            'status' => 200,
            'transfer_stats' => [
                'primary_port' => 9200,
                'total_time' => 1
            ],
            'body' => '{}'
        ];
        $connection->logRequestSuccess($request, $response);
        // Check for localhost:9200 in trace
        foreach ($trace->output as $row) {
            $this->assertStringContainsString('localhost:9200', $row);
        }
        // Check for localhost:9200 in logger
        foreach ($logger->output as $row) {
            if (false !== strpos('info: Request Success', $row)) {
                $this->assertStringContainsString('localhost:9200', $row);
            }
        }
    }

    public function testPortInLogUrlWhenLogRequestFail()
    {
        $logger = new ArrayLogger();
        $trace = new ArrayLogger();

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            [
                'host'   => 'localhost',
                'port'   => 9200,
                'scheme' => 'http',
                'path'   => '/info'
            ],
            [],
            $this->serializer,
            $logger,
            $trace
        );
        $request = [
            'body' => '{}',
            'http_method' => 'GET',
            'headers' => [
                'User-Agent: Testing'
            ]
        ];
        $response = [
            'effective_url' => 'http://localhost/info',
            'status' => 400,
            'transfer_stats' => [
                'primary_port' => 9200,
                'total_time' => 1
            ],
            'body' => '{}'
        ];
        $connection->logRequestFail($request, $response, new Exception());

        // Check for localhost:9200 in trace
        foreach ($trace->output as $row) {
            $this->assertStringContainsString('localhost:9200', $row);
        }
        // Check for localhost:9200 in logger
        foreach ($logger->output as $row) {
            if (false !== strpos('warning: Request Failure:', $row)) {
                $this->assertStringContainsString('localhost:9200', $row);
            }
        }
    }
}
