<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use OpenSearch\Client;
use OpenSearch\GuzzleClientFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Guzzle client factory.
 */
#[Group('integration')]
#[CoversClass(GuzzleClientFactory::class)]
class GuzzleClientFactoryTest extends TestCase
{
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
