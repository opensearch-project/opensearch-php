<?php

namespace OpenSearch\Tests;

use OpenSearch\HttpTransport;
use OpenSearch\RequestFactoryInterface;
use OpenSearch\Serializers\SmartSerializer;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Tests the HTTP transport.
 *
 * @coversDefaultClass \OpenSearch\HttpTransport
 */
class HttpTransportTest extends TestCase
{
    /**
     * @covers ::sendRequest
     */
    public function testHttpTransport(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->once())
            ->method('createRequest')
            ->with($this->anything())
            ->willReturn($request);

        $bodyStream = $this->createMock(StreamInterface::class);
        $bodyStream->expects($this->once())
            ->method('getContents')
            ->willReturn('{"foo":"bar"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($bodyStream);

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $serializer = new SmartSerializer();

        $transport = new HttpTransport($client, $requestFactory, $serializer);
        // Check legacy options are removed.
        // @phpstan-ignore argument.type
        $response = $transport->sendRequest('GET', '/', [], null, ['client' => ['foo' => 'bar']]);

        $this->assertEquals(['foo' => 'bar'], $response);
    }

    /**
     * @covers ::sendRequest
     */
    public function testHttpTransportWithLegacyHeaders(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->identicalTo(['foo' => 'bar']),
            )
            ->willReturn($request);

        $bodyStream = $this->createMock(StreamInterface::class);
        $bodyStream->expects($this->once())
            ->method('getContents')
            ->willReturn('{"foo":"bar"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($bodyStream);

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $serializer = new SmartSerializer();

        $transport = new HttpTransport($client, $requestFactory, $serializer);
        // Check legacy headers are merged.
        // @phpstan-ignore argument.type
        $response = $transport->sendRequest('GET', '/', [], null, ['client' => ['headers' => ['foo' => 'bar']]]);
    }
}
