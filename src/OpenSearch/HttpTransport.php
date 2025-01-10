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
        protected HttpRequestFactoryInterface $requestFactory,
        protected SerializerInterface $serializer,
    ) {
    }

    /**
     * Create a new request.
     */
    public function createHttpRequest(Request $request): RequestInterface
    {
        return $this->requestFactory->createHttpRequest(
            $request->getMethod(),
            $request->getUri(),
            $request->getParams(),
            $request->getBody(),
            $request->getHeaders()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(Request $request): Response
    {
        $httpRequest = $this->createHttpRequest($request);
        $httpResponse = $this->client->sendRequest($httpRequest);
        return new Response(
            $httpResponse->getStatusCode(),
            $httpResponse->getHeaders(),
            $this->serializer->deserialize($httpResponse->getBody()->getContents(), $httpResponse->getHeaders())
        );
    }

}
