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
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Tests\Endpoints;

use OpenSearch\Endpoints\Sql\Query;
use OpenSearch\Exception\UnexpectedValueException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests the SQL Query endpoint.
 */
#[CoversClass(Query::class)]
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
            $this->fail('The format param should be allowed to set but it was not. Format is may not whitelisted.');
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
