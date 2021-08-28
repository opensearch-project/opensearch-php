<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */

namespace OpenSearch\Namespaces;

use OpenSearch\Common\Exceptions\Missing404Exception;
use OpenSearch\Common\Exceptions\RoutingMissingException;
use OpenSearch\Endpoints\AbstractEndpoint;
use OpenSearch\Transport;
use GuzzleHttp\Ring\Future\FutureArrayInterface;

trait BooleanRequestWrapper
{
    /**
     * Perform Request
     *
     * @throws Missing404Exception
     * @throws RoutingMissingException
     */
    public static function performRequest(AbstractEndpoint $endpoint, Transport $transport)
    {
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
