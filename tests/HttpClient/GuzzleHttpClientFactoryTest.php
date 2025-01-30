<?php

declare(strict_types=1);

namespace OpenSearch\Tests\HttpClient;

use OpenSearch\HttpClient\GuzzleHttpClientFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * Test the Guzzle HTTP client factory.
 *
 * @coversDefaultClass \OpenSearch\HttpClient\GuzzleHttpClientFactory
 */
class GuzzleHttpClientFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new GuzzleHttpClientFactory(2);
        $client = $factory->create([
            'base_uri' => 'http://example.com',
            'verify' => true,
            'auth' => ['username', 'password'],
        ]);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
