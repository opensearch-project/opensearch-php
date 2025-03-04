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

namespace OpenSearch\Tests\Serializers;

use OpenSearch\Exception\JsonException;
use OpenSearch\Serializers\SmartSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Class SmartSerializerTest
 *
 * @coversDefaultClass \OpenSearch\Serializers\SmartSerializer
 */
class SmartSerializerTest extends TestCase
{
    /**
     * @var SmartSerializer
     */
    private $serializer;

    public function setUp(): void
    {
        $this->serializer = new SmartSerializer();
    }

    /**
     * @
     */
    public function testThrowJsonException(): void
    {
        $data = '{ "foo" : bar" }';
        try {
            $this->serializer->deserialize($data, []);
            $this->fail('An expected exception has not been raised.');
        } catch (JsonException $e) {
            $this->assertSame(JSON_ERROR_SYNTAX, $e->getCode());
            $this->assertSame('Syntax error', $e->getMessage());
            $this->assertSame($data, $e->getData());
        }
    }

    public function testDeserialize(): void
    {
        $data = '{ "foo" : "bar" }';
        $headers = ['Content-Type' => ['application/json']];

        $result = $this->serializer->deserialize($data, $headers);

        $this->assertEquals('bar', $result['foo']);
    }

    public function testDeserializeWithLegacyContentTypeHeader(): void
    {
        $data = '{ "foo" : "bar" }';
        $headers = ['content_type' => 'application/json'];

        $result = $this->serializer->deserialize($data, $headers);

        $this->assertEquals('bar', $result['foo']);
    }

    public function testDeserializeNonJsonData(): void
    {
        $data = 'plain text data';
        $headers = ['Content-Type' => ['text/plain']];

        $result = $this->serializer->deserialize($data, $headers);

        $this->assertEquals('plain text data', $result);
    }

    public function testDeserializeWithNoContentTypeHeader(): void
    {
        $data = '{ "foo" : "bar" }';
        $headers = [];

        $result = $this->serializer->deserialize($data, $headers);

        $this->assertEquals('bar', $result['foo']);
    }
}
