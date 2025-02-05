<?php

declare(strict_types=1);

namespace OpenSearch\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use OpenSearch\Client;
use Psr\Log\LoggerInterface;

/**
 * Builds an OpenSearch client using Guzzle.
 */
class GuzzleHttpClientFactory implements HttpClientFactoryInterface
{
    public function __construct(
        protected int $maxRetries = 0,
        protected ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options): GuzzleClient
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

        // Merge the default options with the provided options.
        $config = array_merge_recursive($defaults, $options);

        $stack = HandlerStack::create();

        // Handle retries if max_retries is set.
        if ($this->maxRetries > 0) {
            $decider = new GuzzleRetryDecider($this->maxRetries, $this->logger);
            $stack->push(Middleware::retry($decider(...)));
        }

        $config['handler'] = $stack;

        return new GuzzleClient($config);
    }

}
