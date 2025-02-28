<?php

namespace OpenSearch;

use OpenSearch\Aws\SigningClientFactory;
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
        protected ?SigningClientFactory $awsSigningHttpClientFactory = null,
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
        // Clean up the options array for the Symfony HTTP Client.
        if (isset($options['auth_aws'])) {
            $awsAuth = $options['auth_aws'];
            unset($options['auth_aws']);
        }

        $httpClient = (new SymfonyHttpClientFactory($this->maxRetries, $this->logger))->create($options);
        $serializer = new SmartSerializer();

        $requestFactory = new RequestFactory(
            $httpClient,
            $httpClient,
            $httpClient,
            $serializer,
        );

        $transportFactory = (new TransportFactory())
            ->setRequestFactory($requestFactory);

        if (isset($awsAuth)) {
            if (!isset($awsAuth['host'])) {
                $awsAuth['host'] = parse_url($options['base_uri'], PHP_URL_HOST);
            }
            $signingClient = $this->getSigningClientFactory()->create($httpClient, $awsAuth);
            $transportFactory->setHttpClient($signingClient);
        } else {
            $transportFactory->setHttpClient($httpClient);
        }

        return new Client($transportFactory->create(), new EndpointFactory($serializer));
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
