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

namespace OpenSearch\Tests\ConnectionPool;

use OpenSearch\Connections\ConnectionFactory;
use OpenSearch\Serializers\ArrayToJSONSerializer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(ConnectionFactoryTest::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 */
class ConnectionFactoryTest extends TestCase
{
    public function testConnectionWithoutPath(): void
    {
        $factory = new ConnectionFactory(
            function () {
            },
            [],
            new ArrayToJSONSerializer(),
            new NullLogger(),
            new NullLogger()
        );

        $connection = $factory->create(['host' => 'localhost']);
        static::assertNull($connection->getPath());
    }

    #[DataProvider('pathDataProvider')]
    public function testConnectionWithPath(string $path, string $expectedPath): void
    {
        $factory = new ConnectionFactory(
            function () {
            },
            [],
            new ArrayToJSONSerializer(),
            new NullLogger(),
            new NullLogger()
        );

        $connection = $factory->create(['host' => 'localhost', 'path' => $path]);
        static::assertSame($expectedPath, $connection->getPath());
    }

    public static function pathDataProvider(): array
    {
        return [
            ['/', ''],
            ['/foo', '/foo'],
            ['/foo/', '/foo'],
        ];
    }
}
