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

use OpenSearch;
use OpenSearch\Tests\Utility;

/**
 * Class StaticConnectionPoolIntegrationTest
 *
 * @subpackage Tests/StaticConnectionPoolTest
 * @group Integration
 */
class StaticConnectionPoolIntegrationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    private $host;

    public function setUp(): void
    {
        $this->host = Utility::getHost();
    }

    // Issue #636
    public function test404Liveness()
    {
        $client = \OpenSearch\ClientBuilder::create()
            ->setHosts([$this->host])
            ->setConnectionPool(\OpenSearch\ConnectionPool\StaticConnectionPool::class)
            ->setSSLVerification(false)
            ->build();

        $connection = $client->transport->getConnection();

        // Ensure connection is dead
        $connection->markDead();

        // The index doesn't exist, but the server is up so this will return a 404
        $this->assertFalse($client->indices()->exists(['index' => 'not_existing_index']));

        // But the node should be marked as alive since the server responded
        $this->assertTrue($connection->isAlive());
    }
}
