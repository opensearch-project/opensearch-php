<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use OpenSearch\Client;
use OpenSearch\GuzzleClientFactory;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenSearch\GuzzleClientFactory
 *
 * @group integration
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

    /**
     * @covers ::__construct
     * @covers ::create
     */
    public function testCreateWithAwsAuth(): void
    {
        $client = (new GuzzleClientFactory())->create([
            'base_uri' => 'http://localhost:9200',
            'auth_aws' => [
                'region' => 'us-east-1',
                'credentials' => [
                    'access_key' => 'foo',
                    'secret_key' => 'bar',
                ],
            ],
        ]);

        $this->assertInstanceOf(Client::class, $client);
    }
}
