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
use GuzzleHttp\Psr7\HttpFactory;
use OpenSearch\Client;
use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\EndpointFactory;
use OpenSearch\RequestFactory;
use OpenSearch\Serializers\SmartSerializer;
use OpenSearch\TransportFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @subpackage Tests
 * @group      Integration
 * @group      Integration-Min
 */
class ClientIntegrationTest extends TestCase
{
    /**
     * The client under test.
     */
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->getClient();
    }

    public function testInfoNotEmpty()
    {
        $result = $this->client->info();

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('cluster_name', $result);
        $this->assertArrayHasKey('cluster_uuid', $result);
        $this->assertArrayHasKey('version', $result);
    }

    public function testNotFoundError()
    {
        $result = $this->client->get([
            'index' => 'foo',
            'id' => 'bar',
        ]);
        $this->assertEquals(404, $result['status']);
        $error = $result['error'];
        $this->assertEquals('index_not_found_exception', $error['type']);
        $this->assertEquals('no such index [foo]', $error['reason']);
        $this->assertEquals('foo', $error['index']);
    }

    public function testIndexCannotBeEmptyStringForDelete()
    {
        $this->expectException(RuntimeException::class);
        $this->client->delete(
            [
                'index' => '',
                'id' => 'test',
            ]
        );
    }

    public function testIdCannotBeEmptyStringForDelete()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('id is required for delete');

        $this->client->delete(
            [
                'index' => 'test',
                'id' => '',
            ]
        );
    }

    public function testIndexCannotBeArrayOfEmptyStringsForDelete()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('index is required for delete');

        $this->client->delete(
            [
                'index' => ['', '', ''],
                'id' => 'test',
            ]
        );
    }

    public function testIndexCannotBeArrayOfNullsForDelete()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('index is required for delete');

        $this->client->delete(
            [
                'index' => [null, null, null],
                'id' => 'test',
            ]
        );
    }

    /** @test */
    public function sendRawRequest(): void
    {
        $response = $this->client->request('GET', '/');

        $this->assertIsArray($response);
        $expectedKeys = ['name', 'cluster_name', 'cluster_uuid', 'version', 'tagline'];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $response);
        }
    }

    /** @test */
    public function insertDocumentUsingRawRequest(): void
    {
        $randomIndex = 'test_index_' . time();

        $response = $this->client->request('POST', "/$randomIndex/_doc", ['body' => ['field' => 'value']]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('_index', $response);
        $this->assertSame($randomIndex, $response['_index']);
        $this->assertArrayHasKey('_id', $response);
        $this->assertArrayHasKey('result', $response);
        $this->assertSame('created', $response['result']);
    }

    private function getClient(): Client
    {
        $guzzleClient = new GuzzleClient([
            'base_uri' => Utility::getHost(),
            'auth' => ['admin', getenv('OPENSEARCH_INITIAL_ADMIN_PASSWORD')],
            'verify' => false,
            'retries' => 2,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => sprintf(
                    'opensearch-php/%s (%s; PHP %s)',
                    Client::VERSION,
                    PHP_OS,
                    PHP_VERSION
                ),
            ]
        ]);

        $guzzleHttpFactory = new HttpFactory();
        $serializer = new SmartSerializer();

        $requestFactory = new RequestFactory(
            $guzzleHttpFactory,
            $guzzleHttpFactory,
            $guzzleHttpFactory,
            $serializer,
        );

        $transport = (new TransportFactory())
            ->setHttpClient($guzzleClient)
            ->setRequestFactory($requestFactory)
            ->create();

        $endpointFactory = new EndpointFactory();
        return new Client($transport, $endpointFactory, []);
    }

}
