<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Aws;

use Aws\Credentials\CredentialsInterface;
use Aws\Signature\SignatureV4;
use OpenSearch\Aws\SigningClientDecorator;
use OpenSearch\Client;
use OpenSearch\EndpointFactory;
use OpenSearch\RequestFactory;
use OpenSearch\Serializers\SmartSerializer;
use OpenSearch\TransportFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\Response\JsonMockResponse;

/**
 * Tests the signing client decorator with the Symfony HTTP client.
 */
class SigningClientDecoratorSymfonyTest extends TestCase
{
    public function testSymfonyRequestIsSigned(): void
    {
        $response = new JsonMockResponse([
            'foo' => 'bar',
        ]);

        $mockHttpClient = (new MockHttpClient([$response]))->withOptions([
            'base_uri' => 'http://localhost:9200',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Host' => 'localhost:9200',
            ],
        ]);

        $symfonyPsr18Client = (new Psr18Client($mockHttpClient));

        $serializer = new SmartSerializer();

        $requestFactory = new RequestFactory(
            $symfonyPsr18Client,
            $symfonyPsr18Client,
            $symfonyPsr18Client,
            $serializer,
        );

        $credentials = $this->createMock(CredentialsInterface::class);
        $signer = new SignatureV4('es', 'us-east-1');

        $decorator = new SigningClientDecorator(
            $symfonyPsr18Client,
            $credentials,
            $signer,
            [
                'Host' => 'search.host'
            ]
        );

        $transport = (new TransportFactory())
            ->setHttpClient($decorator)
            ->setRequestFactory($requestFactory)
            ->create();

        $endpointFactory = new EndpointFactory();

        $client = new Client($transport, $endpointFactory, []);

        // Send a request to the 'info' endpoint.
        $client->request('GET', '/');

        $this->assertNotEmpty($response->getRequestUrl());
        $requestHeaders = $response->getRequestOptions()['normalized_headers'];
        $this->assertArrayHasKey('authorization', $requestHeaders);
        $this->assertArrayHasKey('x-amz-content-sha256', $requestHeaders);
        $this->assertArrayHasKey('x-amz-date', $requestHeaders);
        $this->assertArrayHasKey('host', $requestHeaders);
    }
}
