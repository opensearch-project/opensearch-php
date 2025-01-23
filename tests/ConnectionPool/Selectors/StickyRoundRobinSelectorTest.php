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

namespace OpenSearch\Tests\ConnectionPool\Selectors;

use OpenSearch;
use OpenSearch\Connections\ConnectionInterface;
use Mockery as m;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(StickyRoundRobinSelectorTest::class . ' is deprecated in 2.3.2 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * Class StickyRoundRobinSelectorTest
 *
 * @subpackage Tests\ConnectionPool\StickyRoundRobinSelectorTest
 *
 * @deprecated in 2.3.2 and will be removed in 3.0.0.
 */
class StickyRoundRobinSelectorTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testTenConnections()
    {
        $roundRobin = new OpenSearch\ConnectionPool\Selectors\StickyRoundRobinSelector();

        $mockConnections = [];
        $mockConnection = m::mock(ConnectionInterface::class);
        $mockConnection->expects('isAlive')->times(16)->andReturns(true);

        $mockConnections[] = $mockConnection;

        foreach (range(0, 9) as $index) {
            $mockConnections[] = m::mock(ConnectionInterface::class);
        }

        foreach (range(0, 15) as $index) {
            $retConnection = $roundRobin->select($mockConnections);

            $this->assertSame($mockConnections[0], $retConnection);
        }
    }

    public function testTenConnectionsFirstDies()
    {
        $roundRobin = new OpenSearch\ConnectionPool\Selectors\StickyRoundRobinSelector();

        $mockConnections = [];
        $mockConnectionNotAlive = m::mock(ConnectionInterface::class);
        $mockConnectionNotAlive->expects('isAlive')->andReturns(false);

        $mockConnections[] = $mockConnectionNotAlive;

        $mockConnectionAlive = m::mock(ConnectionInterface::class);
        $mockConnectionAlive->expects('isAlive')->times(15)->andReturns(true);

        $mockConnections[] = $mockConnectionAlive;

        foreach (range(0, 8) as $index) {
            $mockConnections[] = m::mock(ConnectionInterface::class);
        }

        foreach (range(0, 15) as $index) {
            $retConnection = $roundRobin->select($mockConnections);

            $this->assertSame($mockConnections[1], $retConnection);
        }
    }
}
