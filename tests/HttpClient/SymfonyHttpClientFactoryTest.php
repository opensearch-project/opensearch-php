<?php

declare(strict_types=1);

namespace OpenSearch\Tests\HttpClient;

use OpenSearch\HttpClient\SymfonyHttpClientFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * Test the Symfony HTTP client factory.
 */
#[CoversClass(SymfonyHttpClientFactory::class)]
class SymfonyHttpClientFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new SymfonyHttpClientFactory(2);
        $client = $factory->create([
            'base_uri' => 'http://example.com',
            'verify_peer' => false,
            'auth_basic' => ['username', 'password'],
        ]);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
