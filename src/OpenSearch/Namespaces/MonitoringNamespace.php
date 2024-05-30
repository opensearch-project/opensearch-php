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
