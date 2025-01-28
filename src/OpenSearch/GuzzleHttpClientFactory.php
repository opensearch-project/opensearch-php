<?php

declare(strict_types=1);

namespace OpenSearch;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Client\ClientInterface;

/**
 * Builds an OpenSearch client using Guzzle.
 */
class GuzzleHttpClientFactory implements HttpClientFactoryInterface
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

        $logger = $options['logger'] ?? null;
        unset($options['logger']);

        $maxRetries = $options['max_retries'] ?? 0;
        unset($options['max_retries']);

        // Merge the default options with the provided options.
        $config = array_merge_recursive($defaults, $options);

        $stack = HandlerStack::create();

        // Handle retries if max_retries is set.
        if ($maxRetries > 0) {
            $stack->push(Middleware::retry(function ($retries, $request, $response, $exception) use ($maxRetries, $logger) {
                if ($retries >= $maxRetries) {
                    return false;
                }
                if ($exception instanceof ConnectException) {
                    $logger?->warning(
                        'Retrying request {retries} of {maxRetries}: {exception}',
                        [
                            'retries' => $retries,
                            'maxRetries' => $maxRetries,
                            'exception' => $exception->getMessage(),
                        ]
                    );
                    return true;
                }
                if ($response && $response->getStatusCode() >= 500) {
                    $logger?->warning(
                        'Retrying request {retries} of {maxRetries}: Status code {status}',
                        [
                            'retries' => $retries,
                            'maxRetries' => $maxRetries,
                            'status' => $response->getStatusCode(),
                        ]
                    );
                    return true;
                }
                return false;
            }));
        }

        $config['handler'] = $stack;

        return new GuzzleClient($config);
    }

}
