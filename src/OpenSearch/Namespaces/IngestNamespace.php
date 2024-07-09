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
 * Class IngestNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class IngestNamespace extends AbstractNamespace
{
    /**
     * Deletes a pipeline.
     *
     * $params['id']                      = (string) Pipeline ID or wildcard expression of pipeline IDs used to limit the request. To delete all ingest pipelines in a cluster, use a value of `*`.
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['master_timeout']          = (string) Period to wait for a connection to the master node.If no response is received before the timeout expires, the request fails and returns an error.
     * $params['timeout']                 = (string) Period to wait for a response.If no response is received before the timeout expires, the request fails and returns an error.
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deletePipeline(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ingest\DeletePipeline');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * Returns a pipeline.
     *
     * $params['id']                      = (string) Comma-separated list of pipeline IDs to retrieve. Wildcard (`*`) expressions are supported. To get all ingest pipelines, omit this parameter or use `*`.
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['master_timeout']          = (string) Period to wait for a connection to the master node.If no response is received before the timeout expires, the request fails and returns an error.
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getPipeline(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ingest\GetPipeline');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * Returns a list of the built-in patterns.
     *
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function processorGrok(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ingest\ProcessorGrok');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * Creates or updates a pipeline.
     *
     * $params['id']                      = (string) ID of the ingest pipeline to create or update.
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['master_timeout']          = (string) Period to wait for a connection to the master node. If no response is received before the timeout expires, the request fails and returns an error.
     * $params['timeout']                 = (string) Period to wait for a response. If no response is received before the timeout expires, the request fails and returns an error.
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     * $params['body']                    = (array) The ingest definition (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putPipeline(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ingest\PutPipeline');
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * Allows to simulate a pipeline with example documents.
     *
     * $params['id']          = (string) Pipeline to test. If you don’t specify a `pipeline` in the request body, this parameter is required.
     * $params['verbose']     = (boolean) If `true`, the response includes output data for each processor in the executed pipeline. (Default = false)
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     * $params['body']        = (array) The simulate definition (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function simulate(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ingest\Simulate');
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
}
