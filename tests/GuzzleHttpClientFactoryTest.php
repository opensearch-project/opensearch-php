<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use OpenSearch\GuzzleHttpClientFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Log\NullLogger;

/**
 * Test the Guzzle HTTP client factory.
 *
 * @coversDefaultClass \OpenSearch\GuzzleHttpClientFactory
 */
class GuzzleHttpClientFactoryTest extends TestCase
{
    public function testCreate()
    {
        $client = GuzzleHttpClientFactory::create([
            'base_uri' => 'http://example.com',
            'verify' => true,
            'max_retries' => 2,
            'auth' => ['username', 'password'],
            'logger' => new NullLogger(),
        ]);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
