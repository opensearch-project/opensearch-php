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

namespace OpenSearch\Endpoints\Tasks;

use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{
    /** @var Get */
    private $instance;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        // Instance
        $this->instance = new Get();
    }

    public function testGetTaskWithLowID(): void
    {
        // Arrange
        $expected = '/_tasks/51NfbTU7SamV4sbO5u2JAQ%3A3671';

        $this->instance->setTaskId('51NfbTU7SamV4sbO5u2JAQ:3671');

        // Act
        $result = $this->instance->getURI();

        // Assert
        $this->assertEquals($expected, $result);
    }

}
