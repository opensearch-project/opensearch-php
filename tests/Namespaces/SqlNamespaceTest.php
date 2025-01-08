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

namespace OpenSearch\Tests\Namespaces;

use OpenSearch\EndpointFactory;
use OpenSearch\Namespaces\SqlNamespace;
use OpenSearch\TransportInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests the SQL namespace.
 */
class SqlNamespaceTest extends TestCase
{
    private TransportInterface&MockObject $transport;

    private SqlNamespace $sqlNamespace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transport = $this->createMock(TransportInterface::class);
        $this->sqlNamespace = new SqlNamespace($this->transport, new EndpointFactory());
    }

    public function testQuery(): void
    {
        $this->transport->method('sendRequest')
            ->with('POST', '/_plugins/_sql', [], [
                'query' => 'select * from test',
            ])
            ->willReturn(['foo' => 'bar']);

        $result = $this->sqlNamespace->query([
            'query' => 'select * from test',
        ]);

        $this->assertEquals(['foo' => 'bar'], $result);
    }

    public function testExplain(): void
    {
        $this->transport->method('sendRequest')
            ->with('POST', '/_plugins/_sql/_explain', [], [
                'query' => 'select * from test',
            ])
            ->willReturn(['foo' => 'bar']);

        $result = $this->sqlNamespace->explain([
            'query' => 'select * from test',
        ]);

        $this->assertEquals(['foo' => 'bar'], $result);
    }

    public function testCloseCursor(): void
    {
        $this->transport->method('sendRequest')
            ->with('POST', '/_plugins/_sql/close', [], [
                'cursor' => 'fooo',
            ])
            ->willReturn(['foo' => 'bar']);

        $result = $this->sqlNamespace->closeCursor([
            'cursor' => 'fooo',
        ]);

        $this->assertEquals(['foo' => 'bar'], $result);
    }
}
