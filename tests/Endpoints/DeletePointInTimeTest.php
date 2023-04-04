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

use OpenSearch\Endpoints\DeletePointInTime;

class DeletePointInTimeTest extends \PHPUnit\Framework\TestCase
{
    /** @var DeletePointInTime */
    private $instance;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        // Instance
        $this->instance = new DeletePointInTime();
    }

    public function testGetURIWhenIndexAndIdAreDefined(): void
    {
        // Arrange
        $expected = '/_search/point_in_time';

        $this->instance->setIndex('index');
        $this->instance->setId(10);

        // Act
        $result = $this->instance->getURI();

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetURIWhenIndexIsDefinedAndIdIsNotDefined(): void
    {
        // Arrange
        $expected = '/_search/point_in_time';

        $this->instance->setIndex('index');

        // Act
        $result = $this->instance->getURI();

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetURIWhenIndexIsNotDefined(): void
    {
        // Arrange
        $expected = '/_search/point_in_time';

        // Act
        $result = $this->instance->getURI();

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetMethodWhenIdIsDefined(): void
    {
        // Arrange
        $expected = 'DELETE';

        $this->instance->setId(10);

        // Act
        $result = $this->instance->getMethod();

        // Assert
        $this->assertEquals($expected, $result);
    }

    public function testGetMethodWhenIdIsNotDefined(): void
    {
        // Arrange
        $expected = 'DELETE';

        // Act
        $result = $this->instance->getMethod();

        // Assert
        $this->assertEquals($expected, $result);
    }
}
