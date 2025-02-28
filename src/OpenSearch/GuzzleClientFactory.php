<?php

declare(strict_types=1);

namespace OpenSearch;

use OpenSearch\Aws\SigningClientFactory;
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
        protected ?SigningClientFactory $awsSigningHttpClientFactory = null,
    ) {
    }

    /**
     * @param array<string,mixed> $options
     *   The Guzzle client options.
     */
    public function create(array $options): Client
    {
        // Clean up the options array for the Guzzle HTTP Client.
        if (isset($options['auth_aws'])) {
            $awsAuth = $options['auth_aws'];
            unset($options['auth_aws']);
        }

        $httpClient = (new GuzzleHttpClientFactory($this->maxRetries, $this->logger))->create($options);

        if (isset($awsAuth)) {
            if (!isset($awsAuth['host'])) {
                // Get the host from the base URI.
                $awsAuth['host'] = parse_url($options['base_uri'], PHP_URL_HOST);
            }
            $httpClient = $this->getSigningClientFactory()->create($httpClient, $awsAuth);
        }

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

    /**
     * Gets the AWS signing client factory.
     */
    protected function getSigningClientFactory(): SigningClientFactory
    {
        if ($this->awsSigningHttpClientFactory === null) {
            $this->awsSigningHttpClientFactory = new SigningClientFactory();
        }
        return $this->awsSigningHttpClientFactory;
    }

}
