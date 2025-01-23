<?php

namespace OpenSearch;

use GuzzleHttp\Ring\Future\FutureArrayInterface;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(LegacyTransportWrapper::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * Transport that wraps the legacy transport.
 *
 * @deprecated in 2.4.0 and will be removed in 3.0.0. Use PsrTransport instead.
 */
class LegacyTransportWrapper implements TransportInterface
{
    public function __construct(
        protected Transport $transport,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(
        string $method,
        string $uri,
        array $params = [],
        mixed $body = null,
        array $headers = [],
    ): array|string|null {
        $promise = $this->transport->performRequest($method, $uri, $params, $body);
        $futureArray = $this->transport->resultOrFuture($promise);
        if ($futureArray instanceof FutureArrayInterface) {
            return $futureArray->wait();
        }
        return $futureArray;
    }

}
