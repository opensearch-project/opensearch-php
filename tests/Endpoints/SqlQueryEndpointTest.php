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

use OpenSearch\Common\Exceptions\UnexpectedValueException;
use OpenSearch\Endpoints\Sql\Query;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OpenSearch\Endpoints\Sql\Query
 */
class SqlQueryEndpointTest extends TestCase
{
    /**
     * @var Query
     */
    private $endpoint;

    protected function setUp(): void
    {
        $this->endpoint = new Query();
    }

    public function testFormatIsInParamWhitelist(): void
    {
        $this->assertContains('format', $this->endpoint->getParamWhitelist());
    }

    public function testMethodIsPost(): void
    {
        $this->assertSame('POST', $this->endpoint->getMethod());
    }

    public function testUriIsSqlPlugin(): void
    {
        $this->assertSame('/_plugins/_sql', $this->endpoint->getURI());
    }

    public function testFormatParamIsAllowedToSet(): void
    {
        try {
            $this->endpoint->setParams([
                'format' => 'json',
            ]);
        } catch (UnexpectedValueException $e) {
            $this->fail('The format param should be allowed to set');
        }
    }

    public function testFormatParamIsJson(): void
    {
        $this->endpoint->setParams([
            'format' => 'json',
        ]);

        $params = $this->endpoint->getParams();
        $this->assertSame('json', $params['format']);
    }
}