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

use OpenSearch\Endpoints\AbstractEndpoint;
use OpenSearch\Exception\NotFoundHttpException;
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
     * @throws \OpenSearch\Exception\HttpExceptionInterface
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

        } catch (NotFoundHttpException $e) {
            // Return false for 404 errors.
            return false;
        }
        return true;
    }

}
