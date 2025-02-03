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

namespace OpenSearch\Tests\Endpoints;

use OpenSearch\Endpoints\Create;
use OpenSearch\Exception\RuntimeException;

class CreateTest extends \PHPUnit\Framework\TestCase
{
    /** @var Create */
    private $instance;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        // Instance
        $this->instance = new Create();
    }

    public function testGetURIWhenIndexAndIdAreDefined(): void
    {
        // Arrange
        $expected = '/index/_create/10';

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
        $expected = RuntimeException::class;
        $expectedMessage = 'id is required for create';

        $this->instance->setIndex('index');

        // Assert
        $this->expectException($expected);
        $this->expectExceptionMessage($expectedMessage);

        // Act
        $this->instance->getURI();
    }

    public function testGetURIWhenIdIsDefinedAndIndexIsNotDefined(): void
    {
        // Arrange
        $expected = RuntimeException::class;
        $expectedMessage = 'index is required for create';

        $this->instance->setId('1');

        // Assert
        $this->expectException($expected);
        $this->expectExceptionMessage($expectedMessage);

        // Act
        $this->instance->getURI();
    }

    public function testGetMethodWhenIdIsDefined(): void
    {
        // Arrange
        $expected = 'PUT';

        $this->instance->setId(10);

        // Act
        $result = $this->instance->getMethod();

        // Assert
        $this->assertEquals($expected, $result);
    }

}
