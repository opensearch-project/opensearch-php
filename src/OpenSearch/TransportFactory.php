<?php

namespace OpenSearch;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Serializers\SmartSerializer;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Creates a PSR transport falling back to a discovery mechanism if properties are not specified.
 */
class TransportFactory
{
    private ?PsrRequestFactoryInterface $psrRequestFactory = null;

    private ?StreamFactoryInterface $streamFactory = null;

    private ?UriFactoryInterface $uriFactory = null;

    private ?SerializerInterface $serializer = null;

    private ?RequestFactoryInterface $requestFactory = null;

    private ?ClientInterface $httpClient = null;

    protected function getHttpClient(): ?ClientInterface
    {
        return $this->httpClient;
    }

    public function setHttpClient(?ClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    protected function getRequestFactory(): ?RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function setRequestFactory(?RequestFactoryInterface $requestFactory): static
    {
        $this->requestFactory = $requestFactory;
        return $this;
    }

    protected function getPsrRequestFactory(): PsrRequestFactoryInterface
    {
        if ($this->psrRequestFactory === null) {
            $this->psrRequestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }
        return $this->psrRequestFactory;
    }

    public function setPsrRequestFactory(PsrRequestFactoryInterface $psrRequestFactory): static
    {
        $this->psrRequestFactory = $psrRequestFactory;
        return $this;
    }

    protected function getStreamFactory(): StreamFactoryInterface
    {
        if ($this->streamFactory === null) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }
        return $this->streamFactory;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory): static
    {
        $this->streamFactory = $streamFactory;
        return $this;
    }

    protected function getUriFactory(): UriFactoryInterface
    {
        if ($this->uriFactory === null) {
            $this->uriFactory = Psr17FactoryDiscovery::findUriFactory();
        }
        return $this->uriFactory;
    }

    public function setUriFactory(UriFactoryInterface $uriFactory): static
    {
        $this->uriFactory = $uriFactory;
        return $this;
    }

    protected function getSerializer(): SerializerInterface
    {
        if ($this->serializer === null) {
            $this->serializer = new SmartSerializer();
        }
        return $this->serializer;
    }

    public function setSerializer(SerializerInterface $serializer): static
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * Creates a new transport.
     */
    public function create(): HttpTransport
    {
        if ($this->requestFactory === null) {
            $psrRequestFactory = $this->getPsrRequestFactory();
            $streamFactory = $this->getStreamFactory();
            $uriFactory = $this->getUriFactory();
            $serializer = $this->getSerializer();

            $this->requestFactory = new RequestFactory(
                $psrRequestFactory,
                $streamFactory,
                $uriFactory,
                $serializer
            );
        }
        if ($this->httpClient === null) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        return new HttpTransport($this->httpClient, $this->requestFactory, $this->getSerializer());
    }

}
