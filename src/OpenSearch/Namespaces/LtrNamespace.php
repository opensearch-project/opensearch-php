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

use OpenSearch\Endpoints\Ltr\CacheStats;
use OpenSearch\Endpoints\Ltr\ClearCache;
use OpenSearch\Endpoints\Ltr\CreateDefaultStore;
use OpenSearch\Endpoints\Ltr\CreateStore;
use OpenSearch\Endpoints\Ltr\DeleteDefaultStore;
use OpenSearch\Endpoints\Ltr\DeleteStore;
use OpenSearch\Endpoints\Ltr\GetStore;
use OpenSearch\Endpoints\Ltr\ListStores;
use OpenSearch\Endpoints\Ltr\Stats;

/**
 * Class LtrNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class LtrNamespace extends AbstractNamespace
{
    /**
     * Retrieves cache statistics for all feature stores.
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
    public function cacheStats(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(CacheStats::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Clears the store caches.
     *
     * $params['store']       = (string) The name of the feature store for which to clear the cache.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function clearCache(array $params = [])
    {
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(ClearCache::class);
        $endpoint->setParams($params);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates the default feature store.
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
    public function createDefaultStore(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(CreateDefaultStore::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a new feature store with the specified name.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createStore(array $params = [])
    {
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(CreateStore::class);
        $endpoint->setParams($params);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Deletes the default feature store.
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
    public function deleteDefaultStore(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(DeleteDefaultStore::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Deletes a feature store with the specified name.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteStore(array $params = [])
    {
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteStore::class);
        $endpoint->setParams($params);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Checks if a store exists.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getStore(array $params = [])
    {
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(GetStore::class);
        $endpoint->setParams($params);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Lists all available feature stores.
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
    public function listStores(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(ListStores::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Provides information about the current status of the LTR plugin.
     *
     * $params['node_id']     = (array) A comma-separated list of node IDs or names to limit the returned information; use `_local` to return information from the node you're connecting to, leave empty to get information from all nodes.
     * $params['stat']        = (array) A comma-separated list of stats to retrieve; use `_all` or empty string to retrieve all stats.
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
