<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use OpenSearch\Client;
use OpenSearch\SymfonyClientFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenSearch\SymfonyClientFactory
 */
class SymfonyClientFactoryTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::create
     */
    public function testCreate(): void
    {
        $factory = new SymfonyClientFactory();
        $client = $factory->create([
            'base_uri' => 'https://localhost:9200',
            'auth_basic' => ['admin', 'password'],
            'verify_peer' => false,
        ]);

        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * @covers ::__construct
     */
    public function testLegacyOptions(): void
    {
        $factory = new SymfonyClientFactory();
        $client = $factory->create([
            'base_uri' => 'https://localhost:9200',
            'auth_basic' => ['admin', 'password'],
            'verify_peer' => false,
        ]);

        $exists = $client->indices()->exists(['index' => 'test']);
        $this->assertFalse($exists);
    }
}
