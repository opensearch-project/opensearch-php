<?php

declare(strict_types=1);

namespace OpenSearch;

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
    ): iterable|string|null {
        // Provide legacy support for options.
        $options = $headers;
        $promise = $this->transport->performRequest($method, $uri, $params, $body, $options);
        return $this->transport->resultOrFuture($promise, $options);
    }

}
