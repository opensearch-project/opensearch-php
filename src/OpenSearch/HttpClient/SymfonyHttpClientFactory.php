<?php

declare(strict_types=1);

namespace OpenSearch\HttpClient;

use OpenSearch\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\RetryableHttpClient;

/**
 * Builds an OpenSearch client using Symfony.
 */
class SymfonyHttpClientFactory implements HttpClientFactoryInterface
{
    public function __construct(
        protected int $maxRetries = 0,
        protected ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options): ClientInterface
    {
        if (!isset($options['base_uri'])) {
            throw new \InvalidArgumentException('The base_uri option is required.');
        }
        // Set default configuration.
        $defaults = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => sprintf('opensearch-php/%s (%s; PHP %s)', Client::VERSION, PHP_OS, PHP_VERSION),
            ],
        ];
        $options = array_merge_recursive($defaults, $options);

        $symfonyClient = HttpClient::create()->withOptions($options);

        if ($this->maxRetries > 0) {
            $symfonyClient = new RetryableHttpClient($symfonyClient, null, $this->maxRetries, $this->logger);
        }

        return new Psr18Client($symfonyClient);
    }

}
