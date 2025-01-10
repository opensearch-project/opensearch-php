<?php

declare(strict_types=1);

namespace OpenSearch;

use GuzzleHttp\Ring\Future\FutureArrayInterface;

/**
 * Transport that wraps the legacy transport.
 *
 * @deprecated in 2.3.2 and will be removed in 3.0.0. Use PsrTransport instead.
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
    public function sendRequest(Request $request): Response
    {
        $promise = $this->transport->performRequest(
            $request->getMethod(),
            $request->getUri(),
            $request->getParams(),
            $request->getBody(),
        );
        $futureArray = $this->transport->resultOrFuture($promise);
        if ($futureArray instanceof FutureArrayInterface) {
            // We set status code as 200 because exceptions are thrown for other status codes.
            return new Response(200, [], $futureArray->wait());
        }
        // We set status code as 200 because exceptions are thrown for other status codes.
        return new Response(200, [], $futureArray);
    }

}
