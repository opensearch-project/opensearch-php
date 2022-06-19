<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Handlers;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use GuzzleHttp\Ring\Future\CompletedFutureArray;
use OpenSearch\ClientBuilder;
use OpenSearch\Handlers\SigV4Handler;
use PHPUnit\Framework\TestCase;

class SigV4HandlerTest extends TestCase
{
    public function testSignsRequestsTheSdkDefaultCredentialProviderChain()
    {
        $key = 'foo';
        $toWrap = function (array $ringRequest) use ($key) {
            $this->assertArrayHasKey('X-Amz-Date', $ringRequest['headers']);
            $this->assertArrayHasKey('Authorization', $ringRequest['headers']);
            $this->assertMatchesRegularExpression(
                "~^AWS4-HMAC-SHA256 Credential=$key/\\d{8}/us-west-2/es/aws4_request~",
                $ringRequest['headers']['Authorization'][0]
            );

            return $this->getGenericResponse();
        };
        putenv(CredentialProvider::ENV_KEY . "=$key");
        putenv(CredentialProvider::ENV_SECRET . '=bar');
        $client = $this->getClient(new SigV4Handler('us-west-2', null, $toWrap));

        $client->search([
            'index' => 'index',
            'body' => [
                'query' => [ 'match_all' => (object)[] ],
            ],
        ]);
    }

    public function testSignsWithProvidedCredentials()
    {
        $provider = CredentialProvider::fromCredentials(
            new Credentials('foo', 'bar', 'baz')
        );
        $toWrap = function (array $ringRequest) {
            $this->assertArrayHasKey('X-Amz-Security-Token', $ringRequest['headers']);
            $this->assertSame('baz', $ringRequest['headers']['X-Amz-Security-Token'][0]);
            $this->assertMatchesRegularExpression(
                '~^AWS4-HMAC-SHA256 Credential=foo/\d{8}/us-west-2/es/aws4_request~',
                $ringRequest['headers']['Authorization'][0]
            );

            return $this->getGenericResponse();
        };

        $client = $this->getClient(new SigV4Handler('us-west-2', $provider, $toWrap));

        $client->search([
            'index' => 'index',
            'body' => [
                'query' => [ 'match_all' => (object)[] ],
            ],
        ]);
    }

    public function testEmptyRequestBodiesShouldBeNull()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertNull($ringRequest['body']);

            return $this->getGenericResponse();
        };

        $client = $this->getClient(new SigV4Handler('us-west-2', null, $toWrap));

        $client->indices()->exists(['index' => 'index']);
    }

    public function testNonEmptyRequestBodiesShouldNotBeNull()
    {
        $toWrap = function (array $ringRequest) {
            $this->assertNotNull($ringRequest['body']);

            return $this->getGenericResponse();
        };

        $client = $this->getClient(new SigV4Handler('us-west-2', null, $toWrap));

        $client->search([
            'index' => 'index',
            'body' => [
                'query' => [ 'match_all' => (object)[] ],
            ],
        ]);
    }

    private function getClient(SigV4Handler $handler)
    {
        $builder = ClientBuilder::create()
            ->setHandler($handler);

        if (method_exists($builder, 'allowBadJSONSerialization')) {
            $builder = $builder->allowBadJSONSerialization();
        }

        return $builder->build();
    }

    private function getGenericResponse()
    {
        return new CompletedFutureArray([
            'status' => 200,
            'body' => fopen('php://memory', 'r'),
            'transfer_stats' => ['total_time' => 0],
            'effective_url' => 'https://www.example.com',
        ]);
    }
}
