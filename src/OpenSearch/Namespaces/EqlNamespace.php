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
 * Class EqlNamespace
 *
 */
class EqlNamespace extends AbstractNamespace
{
    /**
     * $params['id'] = (string) The async search ID
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/eql-search-api.html
     *
     * @note This API is BETA and may change in ways that are not backwards compatible
     *
     */
    public function delete(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Eql\Delete');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id']                          = (string) The async search ID
     * $params['wait_for_completion_timeout'] = (time) Specify the time that the request should block waiting for the final response
     * $params['keep_alive']                  = (time) Update the time interval in which the results (partial or final) for this search will be available (Default = 5d)
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/eql-search-api.html
     *
     * @note This API is BETA and may change in ways that are not backwards compatible
     *
     */
    public function get(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Eql\Get');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['index']                       = (string) The name of the index to scope the operation
     * $params['wait_for_completion_timeout'] = (time) Specify the time that the request should block waiting for the final response
     * $params['keep_on_completion']          = (boolean) Control whether the response should be stored in the cluster if it completed within the provided [wait_for_completion] time (default: false) (Default = false)
     * $params['keep_alive']                  = (time) Update the time interval in which the results (partial or final) for this search will be available (Default = 5d)
     * $params['body']                        = (array) Eql request body. Use the `query` to limit the query scope. (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/eql-search-api.html
     *
     * @note This API is BETA and may change in ways that are not backwards compatible
     *
     */
    public function search(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Eql\Search');
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
}
