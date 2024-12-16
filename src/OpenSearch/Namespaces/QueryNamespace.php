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
 * Class QueryNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class QueryNamespace extends AbstractNamespace
{
    /**
     * Deletes specific datasource specified by name.
     *
     * $params['datasource_name'] = (string) The Name of the DataSource to delete.
     * $params['pretty']          = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']           = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace']     = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']          = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']     = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function datasourceDelete(array $params = [])
    {
        $datasource_name = $this->extractArgument($params, 'datasource_name');

        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Query\DatasourceDelete::class);
        $endpoint->setParams($params);
        $endpoint->setDatasourceName($datasource_name);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves specific datasource specified by name.
     *
     * $params['datasource_name'] = (string) The Name of the DataSource to retrieve.
     * $params['pretty']          = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']           = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace']     = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']          = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']     = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function datasourceRetrieve(array $params = [])
    {
        $datasource_name = $this->extractArgument($params, 'datasource_name');

        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Query\DatasourceRetrieve::class);
        $endpoint->setParams($params);
        $endpoint->setDatasourceName($datasource_name);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a new query datasource.
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
    public function datasourcesCreate(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Query\DatasourcesCreate::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves list of all datasources.
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
    public function datasourcesList(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Query\DatasourcesList::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Updates an existing query datasource.
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
    public function datasourcesUpdate(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Query\DatasourcesUpdate::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

}