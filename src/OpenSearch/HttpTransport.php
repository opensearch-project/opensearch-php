<?php

namespace OpenSearch;

use OpenSearch\Serializers\SerializerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Transport that uses PSR-7, PSR-17 and PSR-18 interfaces.
 */
final class HttpTransport implements TransportInterface
{
    public function __construct(
        protected ClientInterface $client,
        protected RequestFactoryInterface $requestFactory,
        protected SerializerInterface $serializer,
    ) {
    }

    /**
     * Create a new request.
     */
    public function createRequest(string $method, string $uri, array $params = [], mixed $body = null, array $headers = []): RequestInterface
    {
        return $this->requestFactory->createRequest($method, $uri, $params, $body, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(
        string $method,
        string $uri,
        array $params = [],
        mixed $body = null,
        array $headers = [],
    ): array|string|null {
        $request = $this->createRequest($method, $uri, $params, $body, $headers);
        $response = $this->client->sendRequest($request);
        return $this->serializer->deserialize($response->getBody()->getContents(), $response->getHeaders());
    }

}
