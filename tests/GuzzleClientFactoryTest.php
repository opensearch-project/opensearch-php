<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use OpenSearch\GuzzleClientFactory;
use PHPUnit\Framework\TestCase;
use OpenSearch\Client;

/**
 * @coversDefaultClass \OpenSearch\GuzzleClientFactory
 */
class GuzzleClientFactoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::create
     */
    public function testCreate(): void
    {
        $factory = new GuzzleClientFactory();
        $client = $factory->create([
            'base_uri' => 'https://localhost:9200',
            'auth' => ['admin', 'password'],
            'verify' => false,
        ]);

        $this->assertInstanceOf(Client::class, $client);
    }
}
