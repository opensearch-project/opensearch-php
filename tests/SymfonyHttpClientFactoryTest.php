<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use OpenSearch\SymfonyHttpClientFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * Test the Symfony HTTP client factory.
 *
 * @coversDefaultClass \OpenSearch\SymfonyHttpClientFactory
 */
class SymfonyHttpClientFactoryTest extends TestCase
{
    public function testCreate()
    {
        $client = SymfonyHttpClientFactory::create([
            'base_uri' => 'http://example.com',
            'verify_peer' => false,
            'max_retries' => 2,
            'auth_basic' => ['username', 'password'],
        ]);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
