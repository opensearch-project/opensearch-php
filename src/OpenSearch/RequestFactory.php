<?php

namespace OpenSearch;

use Http\Discovery\Psr17FactoryDiscovery;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Serializers\SmartSerializer;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final class RequestFactory implements RequestFactoryInterface
{
    private ?SerializerInterface $serializer = null;
    private ?PsrRequestFactoryInterface $requestFactory = null;
    private ?StreamFactoryInterface $streamFactory = null;
    private ?UriFactoryInterface $uriFactory = null;

    public function createRequest(
        string $method,
        string $uri,
        array $params = [],
        string|array|null $body = null,
        array $headers = [],
    ): RequestInterface {
        $uri = $this->getUriFactory()->createUri($uri);
        $uri = $uri->withQuery(http_build_query($params));
        $request = $this->getRequestFactory()->createRequest($method, $uri);
        if ($body !== null) {
            $bodyJson = $this->getSerializer()->serialize($body);
            $bodyStream = $this->getStreamFactory()->createStream($bodyJson);
            $request = $request->withBody($bodyStream);
        }
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        return $request;
    }

    /**
     * Get the serializer to use for serializing request and response bodies.
     */
    public function getSerializer(): ?SerializerInterface
    {
        if ($this->serializer) {
            return $this->serializer;
        }
        return new SmartSerializer();
    }

    /**
     * Set the serializer to use for serializing request and response bodies.
     */
    public function setSerializer(?SerializerInterface $serializer): self
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * Get the request factory to use for creating requests.
     *
     * If no request factory is set, the discovery mechanism will be used to find
     * a request factory.
     *
     * @throws \Http\Discovery\Exception\NotFoundException
     */
    private function getRequestFactory(): PsrRequestFactoryInterface
    {
        if ($this->requestFactory) {
            return $this->requestFactory;
        }

        return $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
    }

    /**
     * Set the request factory to use for creating requests.
     */
    public function setRequestFactory(PsrRequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;
        return $this;
    }

    /**
     * Get the stream factory to use for creating streams.
     *
     * If no stream factory is set, the discovery mechanism will be used to find
     * a stream factory.
     *
     * @throws \Http\Discovery\Exception\NotFoundException
     */
    private function getStreamFactory(): StreamFactoryInterface
    {
        if ($this->streamFactory) {
            return $this->streamFactory;
        }
        return $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
    }

    /**
     * Set the stream factory to use for creating streams.
     */
    public function setStreamFactory(?StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;
        return $this;
    }

    /**
     * Get the URI factory to use for creating URIs.
     *
     * If no URI factory is set, the discovery mechanism will be used to find
     * a URI factory.
     *
     * @throws \Http\Discovery\Exception\NotFoundException
     */
    private function getUriFactory(): UriFactoryInterface
    {
        if ($this->uriFactory) {
            return $this->uriFactory;
        }
        return $this->uriFactory = Psr17FactoryDiscovery::findUriFactory();
    }

    /**
     * Set the URI factory to use for creating URIs.
     */
    public function setUriFactory(?UriFactoryInterface $uriFactory): self
    {
        $this->uriFactory = $uriFactory;
        return $this;
    }

}
