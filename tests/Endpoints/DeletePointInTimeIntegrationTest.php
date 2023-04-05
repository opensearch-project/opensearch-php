<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Tests\Endpoints;

use OpenSearch\Client;
use OpenSearch\Tests\Utility;

/**
 * Class ClosePointInTimeIntegrationTest
 *
 * @subpackage Tests\Endpoints
 * @group Integration
 */
class DeletePointInTimeIntegrationTest extends \PHPUnit\Framework\TestCase
{
    private const INDEX = 'movies';

    /** @var Client */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Utility::getClient();

        if (Utility::isVersionAtLeast($this->client, '2.4.0')) {
            $this->markTestSkipped('Point-in-time tests require OpenSearch >= 2.4.0');
        }

        $this->client->create([
            'index' => self::INDEX,
            'id' => 100,
            'body' => [
                'title' => 'Remember the Titans',
                'director' => 'Boaz Yakin',
                'year' => 2000
            ]
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->client->indices()->delete([
            'index' => self::INDEX,
        ]);
    }

    public function testClosePointInTimeSingle()
    {
        // Arrange
        $result = $this->client->createPointInTime([
            'index' => self::INDEX,
            'keep_alive' => '10m',
        ]);
        $pitId = $result['pit_id'];

        // Act
        $result = $this->client->deletePointInTime([
            'body' => [
                'pit_id' => $pitId,
            ],
        ]);

        // Assert
        $this->assertCount(1, $result['pits']);
        $pit = $result['pits'][0];
        $this->assertTrue($pit['successful']);
        $this->assertSame($pitId, $pit['pit_id']);
    }

    public function testClosePointInTimeMultiple()
    {
        // Arrange
        $result = $this->client->createPointInTime([
            'index' => self::INDEX,
            'keep_alive' => '10m',
        ]);
        $pitId1 = $result['pit_id'];
        $result = $this->client->createPointInTime([
            'index' => self::INDEX,
            'keep_alive' => '1h',
        ]);
        $pitId2 = $result['pit_id'];

        // Act
        $result = $this->client->deletePointInTime([
            'body' => [
                'pit_id' => [$pitId1, $pitId2],
            ],
        ]);

        // Assert
        $this->assertCount(2, $result['pits']);
        foreach ($result['pits'] as $pit) {
            $this->assertTrue($pit['successful']);
            $this->assertTrue(in_array($pit['pit_id'], [$pitId1, $pitId2]));
        }
    }
}
