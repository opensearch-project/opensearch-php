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

use OpenSearch\ClientBuilder;
use OpenSearch\ConnectionPool\SniffingConnectionPool;
use OpenSearch\Tests\Utility;

/**
 * Class SniffingConnectionPoolIntegrationTest
 *
 * @subpackage Tests/SniffingConnectionPoolTest
 * @group Integration
 */
class SniffingConnectionPoolIntegrationTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        static::markTestSkipped("All of Sniffing unit tests use outdated cluster state format, need to redo");
    }

    public function testSniff()
    {
        $client = ClientBuilder::create()
            ->setHosts([Utility::getHost()])
            ->setConnectionPool(SniffingConnectionPool::class, ['sniffingInterval' => -10])
            ->build();

        $pinged = $client->ping();
        $this->assertTrue($pinged);
    }
}
