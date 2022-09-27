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

use OpenSearch\Tests\Utility;

/**
 * Class CreateIntegrationTest
 *
 * @subpackage Tests\Endpoints
 * @group Integration
 */
class CreateIntegrationTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatePassingId()
    {
        // Arrange
        $expectedIndex = 'movies';
        $expectedType = '_doc';
        $expectedResult = 'created';
        $expectedId = 100;

        $client = Utility::getClient();

        // Act
        $result = $client->create([
            'index' => $expectedIndex,
            'id' => $expectedId,
            'body' => [
                'title' => 'Remember the Titans',
                'director' => 'Boaz Yakin',
                'year' => 2000
            ]
        ]);

        // Assert
        $this->assertEquals($expectedIndex, $result['_index']);
        $this->assertEquals($expectedType, $result['_type']);
        $this->assertEquals($expectedResult, $result['result']);
        $this->assertEquals($expectedId, $result['_id']);
    }

    public function testCreateWithoutPassId()
    {
        // Arrange
        $expectedIndex = 'movies';
        $expectedType = '_doc';
        $expectedResult = 'created';

        $client = Utility::getClient();

        // Act
        $result = $client->create([
            'index' => $expectedIndex,
            'body' => [
                'title' => 'Remember the Titans',
                'director' => 'Boaz Yakin',
                'year' => 2000
            ]
        ]);

        // Assert
        $this->assertEquals($expectedIndex, $result['_index']);
        $this->assertEquals($expectedType, $result['_type']);
        $this->assertEquals($expectedResult, $result['result']);
        $this->assertNotEmpty($result['_id']);
    }
}
