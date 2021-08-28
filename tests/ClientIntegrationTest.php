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

use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use OpenSearch\Common\Exceptions\BadRequest400Exception;
use OpenSearch\Common\Exceptions\OpenSearchException;
use OpenSearch\Common\Exceptions\Missing404Exception;
use OpenSearch\Tests\ClientBuilder\ArrayLogger;
use Psr\Log\LogLevel;

/**
 * Class ClientTest
 *
 * @subpackage Tests
 * @group Integration
 */
class ClientIntegrationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * ArrayLogger
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
            ->setLogger($this->logger);

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
        $client = $this->getClient();

        try {
            $result = $client->get([
                'index' => 'foo',
                'id' => 'bar'
            ]);
        } catch (Missing404Exception $e) {
            $this->assertNotEmpty($this->getLevelOutput(LogLevel::WARNING, $this->logger->output));
        }
    }

    public function testIndexCannotBeEmptyStringForDelete()
    {
        $client = $this->getClient();

        $this->expectException(Missing404Exception::class);

        $client->delete(
            [
            'index' => '',
            'id' => 'test'
            ]
        );
    }

    public function testIdCannotBeEmptyStringForDelete()
    {
        $client = $this->getClient();

        $this->expectException(BadRequest400Exception::class);

        $client->delete(
            [
            'index' => 'test',
            'id' => ''
            ]
        );
    }

    public function testIndexCannotBeArrayOfEmptyStringsForDelete()
    {
        $client = $this->getClient();

        $this->expectException(Missing404Exception::class);

        $client->delete(
            [
            'index' => ['', '', ''],
            'id' => 'test'
            ]
        );
    }

    public function testIndexCannotBeArrayOfNullsForDelete()
    {
        $client = $this->getClient();

        $this->expectException(Missing404Exception::class);

        $client->delete(
            [
            'index' => [null, null, null],
            'id' => 'test'
            ]
        );
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
