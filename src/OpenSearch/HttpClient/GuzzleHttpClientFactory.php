<?php

declare(strict_types=1);

namespace OpenSearch\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleLogMiddleware\LogMiddleware;
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

        $middlewares = [];
        if (isset($options['middleware'])) {
            $middlewares = $options['middleware'];
            unset($options['middleware']);
            if (!is_array($middlewares)) {
                $middlewares = [$middlewares];
            }
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

        // Attach any middlewares that look valid.
        foreach ($middlewares as $name => $middleware) {
            if (is_callable($middleware)) {
                // If a name was specified in the options array, use it.
                if (is_int($name) || is_numeric($name)) {
                    $name = '';
                }
                $stack->push($middleware, $name);
            }
        }

        $config['handler'] = $stack;

        return new GuzzleClient($config);
    }

}
