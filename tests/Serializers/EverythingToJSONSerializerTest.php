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

use OpenSearch\Serializers\EverythingToJSONSerializer;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * Class EverythingToJSONSerializerTest
 *
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 */
class EverythingToJSONSerializerTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testSerializeArray()
    {
        $serializer = new EverythingToJSONSerializer();
        $body = ['value' => 'field'];

        $ret = $serializer->serialize($body);

        $body = json_encode($body, JSON_PRESERVE_ZERO_FRACTION);
        $this->assertSame($body, $ret);
    }

    public function testSerializeString()
    {
        $serializer = new EverythingToJSONSerializer();
        $body = 'abc';

        $ret = $serializer->serialize($body);

        $body = '"abc"';
        $this->assertSame($body, $ret);
    }

    public function testDeserializeJSON()
    {
        $serializer = new EverythingToJSONSerializer();
        $body = '{"field":"value"}';

        $ret = $serializer->deserialize($body, []);

        $body = json_decode($body, true);
        $this->assertSame($body, $ret);
    }
}
