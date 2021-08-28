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

namespace OpenSearch\Tests\Serializers;

use OpenSearch\Serializers\EverythingToJSONSerializer;
use Mockery as m;

/**
 * Class EverythingToJSONSerializerTest
 *
 */
class EverythingToJSONSerializerTest extends \PHPUnit\Framework\TestCase
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
