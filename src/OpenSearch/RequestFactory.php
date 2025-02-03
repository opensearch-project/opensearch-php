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
final class RequestFactory implements RequestFactoryInterface
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
    public function createRequest(
        string $method,
        string $uri,
        array $params = [],
        string|array|null $body = null,
        array $headers = [],
    ): RequestInterface {
        $uri = $this->uriFactory->createUri($uri);
        $uri = $uri->withQuery($this->createQuery($params));
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

    /**
     * Create a query string from an array of parameters.
     */
    private function createQuery(array $params): string
    {
        return http_build_query(array_map(function ($value) {
            // Ensure boolean values are serialized as strings.
            if ($value === true) {
                return 'true';
            }
            if ($value === false) {
                return 'false';
            }
            return $value;
        }, $params));
    }

}
