<?php

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

namespace OpenSearch\Tests\Namespaces;

use OpenSearch\Endpoints\Sql\CursorClose;
use OpenSearch\Endpoints\Sql\Explain;
use OpenSearch\Endpoints\Sql\Query;
use OpenSearch\Namespaces\SqlNamespace;
use OpenSearch\Transport;
use PHPUnit\Framework\TestCase;

/**
 * @group Integration
 */
class SqlNamespaceTest extends TestCase
{
    public function testQuery(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->method('performRequest')
            ->with('POST', '/_plugins/_sql', [], [
                'query' => 'select * from test',
            ]);

        $transport->method('resultOrFuture')
            ->willReturn([]);

        $func = static function () {
            return new Query();
        };

        (new SqlNamespace($transport, $func))->query([
            'query' => 'select * from test',
        ]);
    }

    public function testExplain(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->method('performRequest')
            ->with('POST', '/_plugins/_sql/_explain', [], [
                'query' => 'select * from test',
            ]);

        $transport->method('resultOrFuture')
            ->willReturn([]);

        $func = static function () {
            return new Explain();
        };

        (new SqlNamespace($transport, $func))->explain([
            'query' => 'select * from test',
        ]);
    }

    public function testCloseCursor(): void
    {
        $transport = $this->createMock(Transport::class);
        $transport->method('performRequest')
            ->with('POST', '/_plugins/_sql/close', [], [
                'cursor' => 'fooo',
            ]);

        $transport->method('resultOrFuture')
            ->willReturn([]);

        $func = static function () {
            return new CursorClose();
        };

        (new SqlNamespace($transport, $func))->closeCursor([
            'cursor' => 'fooo',
        ]);
    }
}
