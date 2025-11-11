<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use OpenSearch\Client;
use OpenSearch\SymfonyClientFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Symfony client factory.
 */
#[Group('integration')]
#[CoversClass(SymfonyClientFactory::class)]
class SymfonyClientFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new SymfonyClientFactory();
        $client = $factory->create([
            'base_uri' => 'http://localhost:9200',
            'auth_basic' => ['admin', 'password'],
            'verify_peer' => false,
        ]);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testCreateWithAwsAuth(): void
    {
        $client = (new SymfonyClientFactory())->create([
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

    public function testLegacyOptions(): void
    {
        $factory = new SymfonyClientFactory();
        $client = $factory->create([
            'base_uri' => 'http://localhost:9200',
            'auth_basic' => ['admin', 'password'],
            'verify_peer' => false,
        ]);

        $exists = $client->indices()->exists(['index' => 'test']);
        $this->assertFalse($exists);
    }
}
