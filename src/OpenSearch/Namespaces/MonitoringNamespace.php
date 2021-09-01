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

use OpenSearch\Namespaces\AbstractNamespace;

/**
 * Class MonitoringNamespace
 *
 */
class MonitoringNamespace extends AbstractNamespace
{
    /**
     * $params['type']               = DEPRECATED (string) Default document type for items which don't provide one
     * $params['system_id']          = (string) Identifier of the monitored system
     * $params['system_api_version'] = (string) API Version of the monitored system
     * $params['interval']           = (string) Collection interval (e.g., '10s' or '10000ms') of the payload
     * $params['body']               = (array) The operation definition and data (action-data pairs), separated by newlines (Required)
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function bulk(array $params = [])
    {
        $type = $this->extractArgument($params, 'type');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Monitoring\Bulk');
        $endpoint->setParams($params);
        $endpoint->setType($type);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
}
