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

use OpenSearch\Common\Exceptions\Serializer\JsonErrorException;
use OpenSearch\Serializers\SmartSerializer;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * Class SmartSerializerTest
 *
 */
class SmartSerializerTest extends TestCase
{
    public function setUp(): void
    {
        $this->serializer = new SmartSerializer();
    }

    /**
     * @requires PHP 7.3
     * @see https://github.com/elastic/elasticsearch-php/issues/1012
     */
    public function testThrowJsonErrorException()
    {
        $this->expectException(JsonErrorException::class);
        $this->expectExceptionCode(JSON_ERROR_SYNTAX);

        $result = $this->serializer->deserialize('{ "foo" : bar" }', []);
    }
}
