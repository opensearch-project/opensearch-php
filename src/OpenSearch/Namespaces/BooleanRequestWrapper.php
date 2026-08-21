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

namespace OpenSearch\Namespaces;

use GuzzleHttp\Ring\Future\FutureArrayInterface;
use OpenSearch\Common\Exceptions\Missing404Exception;
use OpenSearch\Common\Exceptions\RoutingMissingException;
use OpenSearch\Endpoints\AbstractEndpoint;
use OpenSearch\Exception\NotFoundHttpException;
use OpenSearch\Transport;
use OpenSearch\TransportInterface;

abstract class BooleanRequestWrapper
{
    /**
     * Send a request with a boolean response.
     *
     * @return bool
     *   Returns FALSE for a 404 error, otherwise TRUE.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public static function sendRequest(AbstractEndpoint $endpoint, TransportInterface $transport): bool
    {
        try {
            $transport->sendRequest(
                $endpoint->getMethod(),
                $endpoint->getURI(),
                $endpoint->getParams(),
                $endpoint->getBody(),
                $endpoint->getOptions()
            );

        } catch (NotFoundHttpException|RoutingMissingException $e) {
            // Return false for 404 errors.
            return false;
        }
        return true;
    }

    /**
     * Perform Request
     *
     * @throws Missing404Exception
     * @throws RoutingMissingException
     *
     * @deprecated in 2.4.0 and will be removed in 3.0.0. Use \OpenSearch\Namespaces\BooleanRequestWrapper::sendRequest() instead.
     */
    public static function performRequest(AbstractEndpoint $endpoint, Transport $transport)
    {
        @trigger_error(
            __METHOD__ . '() is deprecated in 2.4.0 and will be removed in 3.0.0. Use \OpenSearch\Namespaces\BooleanRequestWrapper::sendRequest() instead.'
        );
        try {
            $response = $transport->performRequest(
                $endpoint->getMethod(),
                $endpoint->getURI(),
                $endpoint->getParams(),
                $endpoint->getBody(),
                $endpoint->getOptions()
            );

            $response = $transport->resultOrFuture($response, $endpoint->getOptions());
            if (!($response instanceof FutureArrayInterface)) {
                if ($response['status'] === 200) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // async mode, can't easily resolve this...punt to user
                return $response;
            }
        } catch (Missing404Exception $exception) {
            return false;
        } catch (RoutingMissingException $exception) {
            return false;
        }
    }
}
