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

use OpenSearch\EndpointFactoryInterface;
use OpenSearch\Endpoints\Ml\CreateConnector;
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

        $endpointFactory = $this->createMock(EndpointFactoryInterface::class);
        $endpointFactory->method('getEndpoint')
            ->willReturn(new Query());

        (new SqlNamespace($transport, $endpointFactory))->query([
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

        $endpointFactory = $this->createMock(EndpointFactoryInterface::class);
        $endpointFactory->method('getEndpoint')
            ->willReturn(new Explain());

        (new SqlNamespace($transport, $endpointFactory))->explain([
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

        $endpointFactory = $this->createMock(EndpointFactoryInterface::class);
        $endpointFactory->method('getEndpoint')
            ->willReturn(new CursorClose());

        (new SqlNamespace($transport, $endpointFactory))->closeCursor([
            'cursor' => 'fooo',
        ]);
    }
}
