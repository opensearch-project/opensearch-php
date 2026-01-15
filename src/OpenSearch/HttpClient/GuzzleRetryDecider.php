<?php

namespace OpenSearch\HttpClient;

use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Retry decider for Guzzle HTTP Client.
 */
class GuzzleRetryDecider
{
    public function __construct(
        protected ?int $maxRetries = 0,
        protected ?LoggerInterface $logger = null,
    ) {
    }

    public function __invoke(int $retries, ?RequestInterface $request, ?ResponseInterface $response, $exception): bool
    {
        if ($retries >= $this->maxRetries) {
            return false;
        }
        // Increment $retries after comparison for human display in log
        // message.
        if ($exception instanceof ConnectException) {
            $this->logger?->warning(
                'Retrying request {retries} of {maxRetries}: {exception}',
                [
                    'retries' => $retries + 1,
                    'maxRetries' => $this->maxRetries,
                    'exception' => $exception->getMessage(),
                ]
            );
            return true;
        }
        if ($response && $response->getStatusCode() >= 500) {
            $this->logger?->warning(
                'Retrying request {retries} of {maxRetries}: Status code {status}',
                [
                    'retries' => $retries + 1,
                    'maxRetries' => $this->maxRetries,
                    'status' => $response->getStatusCode(),
                ]
            );
            return true;
        }
        // We only retry if there is a 500 or a ConnectException.
        return false;
    }
}
