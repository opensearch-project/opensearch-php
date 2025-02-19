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

use OpenSearch\Endpoints\Insights\TopQueries;

/**
 * Class InsightsNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class InsightsNamespace extends AbstractNamespace
{
    /**
     * Retrieves the top queries based on the given metric type (latency, CPU, or memory).
     *
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function topQueries(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(TopQueries::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

}
