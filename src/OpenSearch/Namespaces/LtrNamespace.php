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

use OpenSearch\Endpoints\Ltr\Stats;

/**
 * Class LtrNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class LtrNamespace extends AbstractNamespace
{
    /**
     * Provides information about the current status of the LTR plugin.
     *
     * $params['node_id']     = (array) Comma-separated list of node IDs or names to limit the returned information; use `_local` to return information from the node you're connecting to, leave empty to get information from all nodes.
     * $params['stat']        = (array) Comma-separated list of stats to retrieve; use `_all` or empty string to retrieve all stats.
     * $params['timeout']     = (string) The time in milliseconds to wait for a response.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function stats(array $params = [])
    {
        $node_id = $this->extractArgument($params, 'node_id');
        $stat = $this->extractArgument($params, 'stat');

        $endpoint = $this->endpointFactory->getEndpoint(Stats::class);
        $endpoint->setParams($params);
        $endpoint->setNodeId($node_id);
        $endpoint->setStat($stat);

        return $this->performRequest($endpoint);
    }

}
