<?php

namespace OpenSearch\Tests\Aws;

use Aws\Credentials\CredentialsInterface;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use OpenSearch\Aws\SigningClientDecorator;
use OpenSearch\Client;
use OpenSearch\EndpointFactory;
use OpenSearch\RequestFactory;
use OpenSearch\Serializers\SmartSerializer;
use OpenSearch\TransportFactory;
use PHPUnit\Framework\TestCase;

/**
 * Tests the signing client decorator.
 */
class SigningClientDecoratorGuzzleTest extends TestCase
{
    public function testGuzzleRequestIsSigned(): void
    {
        // Mock out the Guzzle handler so we can test the client without making a real HTTP request.
        $mockHandler = new MockHandler([
            new Response(200, [], '{"foo":"bar"}'),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $guzzleClient = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:9200',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'handler' => $handlerStack,
        ]);

        $guzzleHttpFactory = new HttpFactory();

        $serializer = new SmartSerializer();

        $requestFactory = new RequestFactory(
            $guzzleHttpFactory,
            $guzzleHttpFactory,
            $guzzleHttpFactory,
            $serializer,
        );
        $credentials = $this->createMock(CredentialsInterface::class);
        $signer = new SignatureV4('es', 'us-east-1');

        $decorator = new SigningClientDecorator($guzzleClient, $credentials, $signer);

        $transport = (new TransportFactory())
            ->setHttpClient($decorator)
            ->setRequestFactory($requestFactory)
            ->create();

        $endpointFactory = new EndpointFactory();
        $client = new Client($transport, $endpointFactory, []);

        $client->request('GET', '/_cat/indices');

        // Check the last request to ensure it was signed and has a host header.
        $lastRequest = $mockHandler->getLastRequest();
        $this->assertEquals('localhost:9200', $lastRequest->getHeader('Host')[0]);
        $this->assertNotEmpty($lastRequest->getHeader('x-amz-content-sha256'));
        $this->assertNotEmpty($lastRequest->getHeader('x-amz-date'));
        $this->assertNotEmpty($lastRequest->getHeader('Authorization'));
    }

}
