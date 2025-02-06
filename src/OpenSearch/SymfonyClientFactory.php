<?php

namespace OpenSearch;

use OpenSearch\HttpClient\SymfonyHttpClientFactory;
use OpenSearch\Serializers\SmartSerializer;
use Psr\Log\LoggerInterface;

/**
 * Creates an OpenSearch client using Symfony HTTP Client.
 */
class SymfonyClientFactory implements ClientFactoryInterface
{
    public function __construct(
        protected int $maxRetries = 0,
        protected ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * Creates a new OpenSearch client using Symfony HTTP Client.
     *
     * @param array<string,mixed> $options
     *   The Symfony HTTP Client options.
     */
    public function create(array $options): Client
    {
        $httpClient = (new SymfonyHttpClientFactory($this->maxRetries, $this->logger))->create($options);

        $serializer = new SmartSerializer();

        $requestFactory = new RequestFactory(
            $httpClient,
            $httpClient,
            $httpClient,
            $serializer,
        );

        $transport = (new TransportFactory())
            ->setHttpClient($httpClient)
            ->setRequestFactory($requestFactory)
            ->create();

        $endpointFactory = new EndpointFactory();
        return new Client($transport, $endpointFactory, []);
    }
}
