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
 * Class GraphNamespace
 *
 */
class GraphNamespace extends AbstractNamespace
{
    /**
     * $params['index']   = (list) A comma-separated list of index names to search; use `_all` or empty string to perform the operation on all indices (Required)
     * $params['type']    = DEPRECATED (list) A comma-separated list of document types to search; leave empty to perform the operation on all types
     * $params['routing'] = (string) Specific routing value
     * $params['timeout'] = (time) Explicit operation timeout
     * $params['body']    = (array) Graph Query DSL
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function explore(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $type = $this->extractArgument($params, 'type');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Graph\Explore');
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setType($type);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
}
