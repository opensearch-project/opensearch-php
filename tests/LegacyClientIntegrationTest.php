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
use OpenSearch\ClientBuilder;
use OpenSearch\Common\Exceptions\Missing404Exception;
use OpenSearch\Exception\RuntimeException;
use OpenSearch\Tests\ClientBuilder\ArrayLogger;
use Psr\Log\LogLevel;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @subpackage Tests
 * @group      Integration
 * @group      Integration-Min
 * @group     Legacy
 *
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 */
class LegacyClientIntegrationTest extends TestCase
{
    /**
     * @var ArrayLogger
     */
    private $logger;

    /**
     * @var string
     */
    private $host;

    public function setUp(): void
    {
        $this->host = Utility::getHost();
        $this->logger = new ArrayLogger();
    }

    private function getClient(): Client
    {
        $client = ClientBuilder::create()
            ->setHosts([$this->host])
            ->setLogger($this->logger)
            ->setSSLVerification(false);

        return $client->build();
    }

    public function testLogRequestSuccessHasInfoNotEmpty()
    {
        $client = $this->getClient();

        $result = $client->info();

        $this->assertNotEmpty($this->getLevelOutput(LogLevel::INFO, $this->logger->output));
    }

    public function testLogRequestSuccessHasPortInInfo()
    {
        $client = $this->getClient();

        $result = $client->info();

        $this->assertStringContainsString('"port"', $this->getLevelOutput(LogLevel::INFO, $this->logger->output));
    }

    public function testLogRequestFailHasWarning()
    {
        $this->expectException(Missing404Exception::class);
        $this->expectExceptionMessage('no such index [foo]');

        $client = $this->getClient();
        $client->get([
            'index' => 'foo',
            'id' => 'bar',
        ]);

    }

    public function testIndexCannotBeEmptyStringForDelete()
    {
        $client = $this->getClient();

        $this->expectException(RuntimeException::class);

        $client->delete(
            [
                'index' => '',
                'id' => 'test',
            ]
        );
    }

    public function testIdCannotBeEmptyStringForDelete()
    {
        $client = $this->getClient();

        $this->expectException(RuntimeException::class);

        $client->delete(
            [
                'index' => 'test',
                'id' => '',
            ]
        );
    }

    public function testIndexCannotBeArrayOfEmptyStringsForDelete()
    {
        $client = $this->getClient();

        $this->expectException(RuntimeException::class);

        $client->delete(
            [
                'index' => ['', '', ''],
                'id' => 'test',
            ]
        );
    }

    public function testIndexCannotBeArrayOfNullsForDelete()
    {
        $client = $this->getClient();

        $this->expectException(RuntimeException::class);

        $client->delete(
            [
                'index' => [null, null, null],
                'id' => 'test',
            ]
        );
    }

    /** @test */
    public function sendRawRequest(): void
    {
        $client = $this->getClient();

        $response = $client->request('GET', '/');

        $this->assertIsArray($response);
        $expectedKeys = ['name', 'cluster_name', 'cluster_uuid', 'version', 'tagline'];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $response);
        }
    }

    /** @test */
    public function insertDocumentUsingRawRequest(): void
    {
        $client = $this->getClient();
        $randomIndex = 'test_index_' .time();

        $response = $client->request('POST', "/$randomIndex/_doc", ['body' => ['field' => 'value']]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('_index', $response);
        $this->assertSame($randomIndex, $response['_index']);
        $this->assertArrayHasKey('_id', $response);
        $this->assertArrayHasKey('result', $response);
        $this->assertSame('created', $response['result']);
    }

    private function getLevelOutput(string $level, array $output): string
    {
        foreach ($output as $out) {
            if (false !== strpos($out, $level)) {
                return $out;
            }
        }

        return '';
    }
}
