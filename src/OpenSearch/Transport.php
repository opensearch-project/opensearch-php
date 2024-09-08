<?php

namespace OpenSearch;

use Http\Discovery\Psr17FactoryDiscovery;
use OpenSearch\Common\Exceptions\BadRequest400Exception;
use OpenSearch\Common\Exceptions\ClientErrorResponseException;
use OpenSearch\Common\Exceptions\Conflict409Exception;
use OpenSearch\Common\Exceptions\Forbidden403Exception;
use OpenSearch\Common\Exceptions\Missing404Exception;
use OpenSearch\Common\Exceptions\NoDocumentsToGetException;
use OpenSearch\Common\Exceptions\NoShardAvailableException;
use OpenSearch\Common\Exceptions\RequestTimeout408Exception;
use OpenSearch\Common\Exceptions\RoutingMissingException;
use OpenSearch\Common\Exceptions\ScriptLangNotSupportedException;
use OpenSearch\Common\Exceptions\ServerErrorResponseException;
use OpenSearch\Common\Exceptions\Unauthorized401Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Transport
{
    private \Elastic\Transport\Transport $transport;

    public function __construct(\Elastic\Transport\Transport $transport)
    {
        $this->transport = $transport;
    }

    public function performRequest(string $method, string $uri, array $params = [], $body = null, array $options = []): ResponseInterface
    {
        if (!empty($params)) {
            $uri .= '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        }

        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();

        $request = $requestFactory->createRequest(
            $method,
            $uri
        );

        if ($body) {
            $request = $request->withHeader('Content-Type', 'application/json');
            $request = $request->withBody($requestFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR)));
        }

        return $this->transport->sendRequest($request->withHeader('Accept', 'application/json'));
    }

    public function resultOrFuture(ResponseInterface $response): ?array
    {
        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            $this->handleClientErrorResponse($response);
        }

        if ($response->getStatusCode() >= 500) {
            $this->handleServerErrorResponse($response);
        }

        if (str_starts_with($response->getHeader('Content-Type')[0], 'application/json')) {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new ClientErrorResponseException(
            $response->getBody()->getContents(),
            $response->getStatusCode(),
        );
    }

    private function handleClientErrorResponse(ResponseInterface $response): void
    {
        $body = $response->getBody();

        switch ($response->getStatusCode()) {
            case 401:
                throw new Unauthorized401Exception($body, $response->getStatusCode());
            case 403:
                throw new Forbidden403Exception($body, $response->getStatusCode());
            case 404:
                throw new Missing404Exception($body, $response->getStatusCode());
            case 409:
                throw new Conflict409Exception($body, $response->getStatusCode());
            case 408:
                throw new RequestTimeout408Exception($body, $response->getStatusCode());
            default:
                if ($response->getStatusCode() === 400 && str_contains($body, 'script_lang not supported')) {
                    throw new ScriptLangNotSupportedException($body, $response->getStatusCode());
                }

                throw new BadRequest400Exception($body, $response->getStatusCode());
        }
    }

    private function handleServerErrorResponse(ResponseInterface $response): void
    {
        $responseBody = $response->getBody();
        $statusCode = $response->getStatusCode();

        if ($statusCode === 500 && str_contains($responseBody, "RoutingMissingException")) {
            throw new RoutingMissingException($responseBody, $statusCode);
        } elseif ($statusCode === 500 && preg_match('/ActionRequestValidationException.+ no documents to get/', $responseBody) === 1) {
            throw new NoDocumentsToGetException($responseBody, $statusCode);
        } elseif ($statusCode === 500 && str_contains($responseBody, 'NoShardAvailableActionException')) {
            throw new NoShardAvailableException($responseBody, $statusCode);
        } else {
            throw new ServerErrorResponseException(
                $responseBody,
                $statusCode
            );
        }
    }

    public function getLastResponse(): ResponseInterface
    {
        return $this->transport->getLastResponse();
    }

    public function getLastRequest(): RequestInterface
    {
        return $this->transport->getLastRequest();
    }
}
