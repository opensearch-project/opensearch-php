<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Aws;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use OpenSearch\Aws\SigningClientFactory;
use OpenSearch\HttpClient\SymfonyHttpClientFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * Tests the signing client factory.
 */
#[CoversClass(SigningClientFactory::class)]
class SigningClientFactoryTest extends TestCase
{
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

    public function testCreateWithCallableProvider(): void
    {
        $symfonyClient = (new SymfonyHttpClientFactory())->create([
            'base_uri' => 'http://localhost:9200',
        ]);

        $provider = CredentialProvider::fromCredentials(new Credentials('foo', 'bar'));

        $client = (new SigningClientFactory(provider: $provider))->create($symfonyClient, [
            'host' => 'example.com',
            'region' => 'us-east-1',
        ]);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testCreateWithDeprecatedCredentialProviderInstance(): void
    {
        $symfonyClient = (new SymfonyHttpClientFactory())->create([
            'base_uri' => 'http://localhost:9200',
        ]);

        $deprecation = null;
        set_error_handler(static function (int $errno, string $errstr) use (&$deprecation): bool {
            $deprecation = $errstr;
            return true;
        }, \E_USER_DEPRECATED);

        try {
            $factory = new SigningClientFactory(provider: new CredentialProvider());
        } finally {
            restore_error_handler();
        }

        $this->assertNotNull($deprecation, 'Expected a deprecation notice when passing a CredentialProvider instance.');
        $this->assertStringContainsString('deprecated', $deprecation);

        // The deprecated provider is ignored; the factory still produces a working client.
        $client = $factory->create($symfonyClient, [
            'host' => 'example.com',
            'region' => 'us-east-1',
            'credentials' => [
                'access_key' => 'foo',
                'secret_key' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

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
