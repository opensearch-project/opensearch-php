<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * OpenSearch PHP client
 *
 * @link      https://github.com/opensearch-project/opensearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch;

use Fig\Http\Message\StatusCodeInterface;
use Http\Discovery\Psr17FactoryDiscovery;
use OpenSearch\Common\Exceptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Transport
{
    private \Elastic\Transport\Transport $transport;

    public function __construct(\Elastic\Transport\Transport $transport)
    {
        $this->transport = $transport;
    }


    /**
     * Perform a request to the Cluster
     *
     * @param string     $method  HTTP method to use
     * @param string     $uri     HTTP URI to send request to
     * @param array<string, mixed> $params  Optional query parameters
     * @param mixed|null $body    Optional query body
     * @param array      $options
     *
     * @throws \OpenSearch\Common\Exceptions\NoNodesAvailableException|\Exception
     */
    public function performRequest(
        string $method,
        string $uri,
        array $params = [],
        mixed $body = null,
        array $options = []
    ): ResponseInterface {
        if (! empty($params)) {
            $uri .= '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        }

        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();

        $request = $requestFactory->createRequest(
            $method,
            $uri
        );

        if ($body) {
            $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody($requestFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR)));
        }

        return $this->transport->sendRequest($request->withHeader('Accept', 'application/json'));
    }

    public function resultOrFuture(ResponseInterface $response)
    {
        if (
            $response->getStatusCode() >= StatusCodeInterface::STATUS_BAD_REQUEST &&
            $response->getStatusCode() < StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR
        ) {
            $this->handleClientErrorResponse($response);
        }

        if ($response->getStatusCode() >= StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR) {
            $this->handleServerErrorResponse($response);
        }

        if (str_starts_with($response->getHeader('Content-Type')[0], 'application/json')) {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new Exceptions\ClientErrorResponseException(
            $response->getBody()->getContents(),
            $response->getStatusCode(),
        );
    }

    private function handleClientErrorResponse(ResponseInterface $response): void
    {
        $body = $response->getBody()->getContents();

        switch ($response->getStatusCode()) {
            case StatusCodeInterface::STATUS_UNAUTHORIZED:
                throw new Exceptions\Unauthorized401Exception($body, $response->getStatusCode());
            case StatusCodeInterface::STATUS_FORBIDDEN:
                throw new Exceptions\Forbidden403Exception($body, $response->getStatusCode());
            case StatusCodeInterface::STATUS_NOT_FOUND:
                throw new Exceptions\Missing404Exception($body, $response->getStatusCode());
            case StatusCodeInterface::STATUS_CONFLICT:
                throw new Exceptions\Conflict409Exception($body, $response->getStatusCode());
            case StatusCodeInterface::STATUS_REQUEST_TIMEOUT:
                throw new Exceptions\RequestTimeout408Exception($body, $response->getStatusCode());
            default:
                if (
                    $response->getStatusCode() === StatusCodeInterface::STATUS_BAD_REQUEST &&
                    str_contains($body, 'script_lang not supported')
                ) {
                    throw new Exceptions\ScriptLangNotSupportedException($body, $response->getStatusCode());
                }

                throw new Exceptions\BadRequest400Exception($body, $response->getStatusCode());
        }
    }

    private function handleServerErrorResponse(ResponseInterface $response): void
    {
        $responseBody = $response->getBody()->getContents();
        $statusCode = $response->getStatusCode();

        if (
            $statusCode === StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR &&
            str_contains($responseBody, "RoutingMissingException")
        ) {
            throw new Exceptions\RoutingMissingException($responseBody, $statusCode);
        } elseif (
            $statusCode === StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR &&
            preg_match('/ActionRequestValidationException.+ no documents to get/', $responseBody) === 1
        ) {
            throw new Exceptions\NoDocumentsToGetException($responseBody, $statusCode);
        } elseif (
            $statusCode === StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR &&
            str_contains($responseBody, 'NoShardAvailableActionException')
        ) {
            throw new Exceptions\NoShardAvailableException($responseBody, $statusCode);
        } else {
            throw new Exceptions\ServerErrorResponseException(
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
