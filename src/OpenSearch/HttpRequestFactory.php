<?php

namespace OpenSearch;

use OpenSearch\Serializers\SerializerInterface;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Request factory that uses PSR-7, PSR-17 and PSR-18 interfaces.
 */
final class HttpRequestFactory implements HttpRequestFactoryInterface
{
    public function __construct(
        protected PsrRequestFactoryInterface $psrRequestFactory,
        protected StreamFactoryInterface $streamFactory,
        protected UriFactoryInterface $uriFactory,
        protected SerializerInterface $serializer,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function createHttpRequest(
        string $method,
        string $uri,
        array $params = [],
        string|array|null $body = null,
        array $headers = [],
    ): RequestInterface {
        $uri = $this->uriFactory->createUri($uri);
        $uri = $uri->withQuery(http_build_query($params));
        $request = $this->psrRequestFactory->createRequest($method, $uri);
        if ($body !== null) {
            $bodyJson = $this->serializer->serialize($body);
            $bodyStream = $this->streamFactory->createStream($bodyJson);
            $request = $request->withBody($bodyStream);
        }
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        return $request;
    }

}
