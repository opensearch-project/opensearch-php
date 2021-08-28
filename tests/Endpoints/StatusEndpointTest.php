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

namespace OpenSearch\Tests\Endpoints;

use OpenSearch\Endpoints\Snapshot\Status;
use OpenSearch\Common\Exceptions;

class StatusEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \OpenSearch\Endpoints\Snapshot\Status
     */
    private $endpoint;

    protected function setUp(): void
    {
        $this->endpoint = new Status();
    }

    public static function statusParams()
    {
        return [
            [
                'repository' => 'my_backup',
                'snapshot' => null,
                'expected' => '/_snapshot/my_backup/_status',
            ],
            [
                'repository' => 'my_backup',
                'snapshot' => 'snapshot_1',
                'expected' => '/_snapshot/my_backup/snapshot_1/_status',
            ],
        ];
    }

    /**
     * @dataProvider statusParams
     */
    public function testGetUriReturnsAppropriateUri($repository, $snapshot, $expected)
    {
        if ($repository) {
            $this->endpoint->setRepository($repository);
        }

        if ($snapshot) {
            $this->endpoint->setSnapshot($snapshot);
        }

        $this->assertSame($expected, $this->endpoint->getURI());
    }
}
