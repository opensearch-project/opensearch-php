<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Namespaces;

use OpenSearch\Client;
use OpenSearch\EndpointFactory;
use OpenSearch\TransportInterface;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    public function testSearchDoesNotInhereitPreviousParameters(): void
    {
        $transport = $this->createMock(TransportInterface::class);

        $calledUrls = [];

        $transport->method('sendRequest')
            ->willReturnCallback(function ($method, $uri, $params, $body) use (&$calledUrls) {
                $calledUrls[] = $uri;
                return [];
            });

        $client = new Client($transport, new EndpointFactory());

        $client->search([
            'index' => 'test',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);

        $client->search([
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);

        static::assertSame([
            '/test/_search',
            '/_search',
        ], $calledUrls);
    }
}
