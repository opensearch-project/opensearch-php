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

namespace OpenSearch\Tests\Helper\Iterators;

use OpenSearch\Helper\Iterators\SearchHitIterator;
use OpenSearch\Helper\Iterators\SearchResponseIterator;
use Mockery;

/**
 * Class SearchResponseIteratorTest
 *
 */
class SearchHitIteratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SearchResponseIterator
     */
    private $searchResponse;

    public function setUp(): void
    {
        $this->searchResponse = Mockery::mock(SearchResponseIterator::class);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testWithNoResults()
    {
        $searchHit = new SearchHitIterator($this->searchResponse);
        $this->assertCount(0, $searchHit);
    }

    public function testWithHits()
    {
        $this->searchResponse->shouldReceive('rewind')
            ->once()
            ->ordered();

        $this->searchResponse->shouldReceive('current')
            ->andReturn(
                [
                'hits' => [
                    'hits' => [
                        [ 'foo' => 'bar0' ],
                        [ 'foo' => 'bar1' ],
                        [ 'foo' => 'bar2' ]
                    ],
                    'total' => 3
                ]
                ],
                [
                'hits' => [
                    'hits' => [
                        [ 'foo' => 'bar0' ],
                        [ 'foo' => 'bar1' ],
                        [ 'foo' => 'bar2' ]
                    ],
                    'total' => 3
                ]
                ],
                [
                'hits' => [
                    'hits' => [
                        [ 'foo' => 'bar0' ],
                        [ 'foo' => 'bar1' ],
                        [ 'foo' => 'bar2' ]
                    ],
                    'total' => 3
                ]
                ],
                [
                'hits' => [
                    'hits' => [
                        [ 'foo' => 'bar0' ],
                        [ 'foo' => 'bar1' ],
                        [ 'foo' => 'bar2' ]
                    ],
                    'total' => 3
                ]
                ],
                [
                'hits' => [
                    'hits' => [
                        [ 'foo' => 'bar3' ],
                        [ 'foo' => 'bar4' ]
                    ],
                    'total' => 2
                ]
                ],
                [
                'hits' => [
                    'hits' => [
                        [ 'foo' => 'bar3' ],
                        [ 'foo' => 'bar4' ]
                    ],
                    'total' => 2
                ]
                ]
            );

        $this->searchResponse->shouldReceive('valid')
            ->andReturn(true, true, true, false);

        $this->searchResponse->shouldReceive('next')
            ->times(2)
            ->ordered();

        $responses = new SearchHitIterator($this->searchResponse);
        $i = 0;
        foreach ($responses as $key => $value) {
            $this->assertEquals($i, $key);
            $this->assertEquals("bar$i", $value['foo']);
            $i++;
        }
    }
}
