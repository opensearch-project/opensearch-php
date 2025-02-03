<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Handlers;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use GuzzleHttp\Ring\Future\CompletedFutureArray;
use OpenSearch\ClientBuilder;
use PHPUnit\Framework\TestCase;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(SigV4HandlerTest::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * @group legacy
 * @deprecated in 2.4.0 and will be removed in 3.0.0. Use \OpenSearch\Aws\SigV4RequestFactory instead.
 */
class SigV4HandlerTest extends TestCase
{
    private const ENV_KEYS_USED = [CredentialProvider::ENV_KEY, CredentialProvider::ENV_SECRET];

    /**
     * @var array<string, string|false>
     */
    private $envTemp = [];

    protected function setUp(): void
    {
        $this->envTemp = array_combine(self::ENV_KEYS_USED, array_map(
            function ($envVarName) {
                $current = getenv($envVarName);
                putenv($envVarName);
                return $current;
            },
            self::ENV_KEYS_USED
        ));
    }

    protected function tearDown(): void
    {
        foreach ($this->envTemp as $key => $value) {
            putenv("$key=$value");
        }
        $this->envTemp = [];
    }

    public function testSignsRequestsTheSdkDefaultCredentialProviderChain()
    {
        $key = 'foo';
        $toWrap = function (array $ringRequest) use ($key) {
            $this->assertArrayHasKey('X-Amz-Date', $ringRequest['headers']);
            $this->assertArrayHasKey('Authorization', $ringRequest['headers']);
            $this->assertArrayHasKey('x-amz-content-sha256', $ringRequest['headers']);
            $this->assertMatchesRegularExpression(
                "~^AWS4-HMAC-SHA256 Credential=$key/\\d{8}/us-west-2/es/aws4_request~",
                $ringRequest['headers']['Authorization'][0]
            );

            return $this->getGenericResponse();
        };
        putenv(CredentialProvider::ENV_KEY . "=$key");
        putenv(CredentialProvider::ENV_SECRET . '=bar');
        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setSigV4Region('us-west-2')
            ->setSigV4CredentialProvider(true)
            ->build();

        $client->search([
            'index' => 'index',
            'body' => [
                'query' => [ 'match_all' => (object)[] ],
            ],
        ]);
    }

    public function testSignsWithProvidedCredentials()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertArrayHasKey('X-Amz-Security-Token', $ringRequest['headers']);
            $this->assertSame('baz', $ringRequest['headers']['X-Amz-Security-Token'][0]);
            $this->assertArrayHasKey('x-amz-content-sha256', $ringRequest['headers']);
            $this->assertMatchesRegularExpression(
                '~^AWS4-HMAC-SHA256 Credential=foo/\d{8}/us-west-2/es/aws4_request~',
                $ringRequest['headers']['Authorization'][0]
            );

            return $this->getGenericResponse();
        };

        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setSigV4Region('us-west-2')
            ->setSigV4CredentialProvider(new Credentials('foo', 'bar', 'baz'))
            ->build();

        $client->search([
            'index' => 'index',
            'body' => [
                'query' => [ 'match_all' => (object)[] ],
            ],
        ]);
    }

    public function testSignsWithProvidedCredentialsAndService()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertArrayHasKey('X-Amz-Security-Token', $ringRequest['headers']);
            $this->assertSame('baz', $ringRequest['headers']['X-Amz-Security-Token'][0]);
            $this->assertArrayHasKey('x-amz-content-sha256', $ringRequest['headers']);
            $this->assertMatchesRegularExpression(
                '~^AWS4-HMAC-SHA256 Credential=foo/\d{8}/us-west-2/aoss/aws4_request~',
                $ringRequest['headers']['Authorization'][0]
            );

            return $this->getGenericResponse();
        };

        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setSigV4Region('us-west-2')
            ->setSigV4Service('aoss')
            ->setSigV4CredentialProvider(new Credentials('foo', 'bar', 'baz'))
            ->build();

        $client->search([
            'index' => 'index',
            'body' => [
                'query' => ['match_all' => (object)[]],
            ],
        ]);
    }

    public function testEmptyBodyProducesCorrectSha256()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertArrayHasKey('X-Amz-Security-Token', $ringRequest['headers']);
            $this->assertSame('baz', $ringRequest['headers']['X-Amz-Security-Token'][0]);
            $this->assertArrayHasKey('x-amz-content-sha256', $ringRequest['headers']);
            $this->assertSame($ringRequest['headers']['x-amz-content-sha256'][0], 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
            $this->assertMatchesRegularExpression(
                '~^AWS4-HMAC-SHA256 Credential=foo/\d{8}/us-west-2/aoss/aws4_request~',
                $ringRequest['headers']['Authorization'][0]
            );

            return $this->getGenericResponse();
        };

        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setSigV4Region('us-west-2')
            ->setSigV4Service('aoss')
            ->setSigV4CredentialProvider(new Credentials('foo', 'bar', 'baz'))
            ->build();

        $client->indices()->exists(['index' => 'index']);
    }

    public function testEmptyRequestBodiesShouldBeNull()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertNull($ringRequest['body']);

            return $this->getGenericResponse();
        };

        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setSigV4Region('us-west-2')
            ->setSigV4CredentialProvider(new Credentials('foo', 'bar', 'baz'))
            ->build();

        $client->indices()->exists(['index' => 'index']);
    }

    public function testNonEmptyRequestBodiesShouldNotBeNull()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertNotNull($ringRequest['body']);

            return $this->getGenericResponse();
        };

        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setSigV4Region('us-west-2')
            ->setSigV4CredentialProvider(new Credentials('foo', 'bar', 'baz'))
            ->build();

        $client->search([
            'index' => 'index',
            'body' => [
                'query' => [ 'match_all' => (object)[] ],
            ],
        ]);
    }

    public function testClientParametersShouldBePassedToHandler()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertArrayHasKey('client', $ringRequest);
            $this->assertArrayHasKey('timeout', $ringRequest['client']);
            $this->assertArrayHasKey('connect_timeout', $ringRequest['client']);

            return $this->getGenericResponse();
        };

        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setSigV4Region('us-west-2')
            ->setSigV4CredentialProvider(new Credentials('foo', 'bar', 'baz'))
            ->setConnectionParams(['client' => ['timeout' => 5, 'connect_timeout' => 5]])
            ->build();

        $client->indices()->exists(['index' => 'index']);
    }

    public function testClientPortDeterminedByURL()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertArrayNotHasKey(CURLOPT_PORT, $ringRequest['client']['curl']);

            return $this->getGenericResponse();
        };

        $client = ClientBuilder::create()
            ->setHandler($toWrap)
            ->setHosts(['https://search--hgkaewb2ytci3t3y6yghh5m5vje.eu-central-1.es.amazonaws.com'])
            ->setSigV4Region('us-west-2')
            ->setSigV4CredentialProvider(new Credentials('foo', 'bar', 'baz'))
            ->build();

        $client->indices()->exists(['index' => 'index']);
    }

    private function getGenericResponse(): CompletedFutureArray
    {
        return new CompletedFutureArray([
            'status' => 200,
            'body' => fopen('php://memory', 'r'),
            'transfer_stats' => ['total_time' => 0],
            'effective_url' => 'https://www.example.com',
        ]);
    }
}
