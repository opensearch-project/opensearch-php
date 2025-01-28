<?php

declare(strict_types=1);

namespace OpenSearch;

use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpClient\RetryableHttpClient;

/**
 * Builds an OpenSearch client using Symfony.
 */
class SymfonyHttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public static function create(array $options): ClientInterface
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
        $maxRetries = $options['max_retries'] ?? 0;
        unset($options['max_retries']);

        $logger = $options['logger'] ?? null;
        unset($options['logger']);

        $symfonyClient = HttpClient::create()->withOptions($options);

        if ($maxRetries > 0) {
            $symfonyClient = new RetryableHttpClient($symfonyClient, null, $maxRetries, $logger);
        }

        return new Psr18Client($symfonyClient);
    }

}
