<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Aws;

use OpenSearch\Aws\SigningClientFactory;
use OpenSearch\HttpClient\SymfonyHttpClientFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @coversDefaultClass \OpenSearch\Aws\SigningClientFactory
 */
class SigningClientFactoryTest extends TestCase
{
    /**
     * @covers ::create
     */
    public function testCreate(): void
    {
        $symfonyClient = (new SymfonyHttpClientFactory())->create([
            'base_uri' => 'http://localhost:9200',
        ]);
        $client = (new SigningClientFactory())->create($symfonyClient, [
            'host' => 'example.com',
            'region' => 'us-east-1',
            'credentials' => [
                'access_key' => 'foo',
                'secret_key' => 'bar',
            ],
        ]);

        // Check we get a client back.
        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    /**
     * @covers ::create
     */
    public function testValidateService(): void
    {
        $symfonyClient = (new SymfonyHttpClientFactory())->create([
            'base_uri' => 'http://localhost:9200',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The service option must be either "es" or "aoss".');

        (new SigningClientFactory())->create($symfonyClient, [
            'host' => 'example.com',
            'region' => 'us-east-1',
            'service' => 'foo',
            'credentials' => [
                'access_key' => 'foo',
                'secret_key' => 'bar',
            ],
        ]);
    }
}
