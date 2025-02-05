<?php

declare(strict_types=1);

namespace OpenSearch;

use OpenSearch\HttpClient\GuzzleHttpClientFactory;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7\HttpFactory;
use OpenSearch\Serializers\SmartSerializer;

/**
 * Creates an OpenSearch client using Guzzle.
 */
class GuzzleClientFactory implements ClientFactoryInterface
{
    public function __construct(
        protected int $maxRetries = 0,
        protected ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * @param array<string,mixed> $options
     *   The Guzzle client options.
     */
    public function create(array $options): Client
    {
        $httpClient = (new GuzzleHttpClientFactory($this->maxRetries, $this->logger))->create($options);
        $httpFactory = new HttpFactory();

        $serializer = new SmartSerializer();

        $requestFactory = new RequestFactory(
            $httpFactory,
            $httpFactory,
            $httpFactory,
            $serializer,
        );

        $transport = (new TransportFactory())
            ->setHttpClient($httpClient)
            ->setRequestFactory($requestFactory)
            ->create();

        return new Client($transport, new EndpointFactory($serializer), []);
    }
}
