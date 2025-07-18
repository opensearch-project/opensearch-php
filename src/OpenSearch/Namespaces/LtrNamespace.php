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

use OpenSearch\Endpoints\Ltr\AddFeaturesToSet;
use OpenSearch\Endpoints\Ltr\AddFeaturesToSetByQuery;
use OpenSearch\Endpoints\Ltr\CacheStats;
use OpenSearch\Endpoints\Ltr\ClearCache;
use OpenSearch\Endpoints\Ltr\CreateDefaultStore;
use OpenSearch\Endpoints\Ltr\CreateFeature;
use OpenSearch\Endpoints\Ltr\CreateFeatureset;
use OpenSearch\Endpoints\Ltr\CreateModel;
use OpenSearch\Endpoints\Ltr\CreateModelFromSet;
use OpenSearch\Endpoints\Ltr\CreateStore;
use OpenSearch\Endpoints\Ltr\DeleteDefaultStore;
use OpenSearch\Endpoints\Ltr\DeleteFeature;
use OpenSearch\Endpoints\Ltr\DeleteFeatureset;
use OpenSearch\Endpoints\Ltr\DeleteModel;
use OpenSearch\Endpoints\Ltr\DeleteStore;
use OpenSearch\Endpoints\Ltr\GetFeature;
use OpenSearch\Endpoints\Ltr\GetFeatureset;
use OpenSearch\Endpoints\Ltr\GetModel;
use OpenSearch\Endpoints\Ltr\GetStore;
use OpenSearch\Endpoints\Ltr\ListStores;
use OpenSearch\Endpoints\Ltr\SearchFeatures;
use OpenSearch\Endpoints\Ltr\SearchFeaturesets;
use OpenSearch\Endpoints\Ltr\SearchModels;
use OpenSearch\Endpoints\Ltr\Stats;
use OpenSearch\Endpoints\Ltr\UpdateFeature;
use OpenSearch\Endpoints\Ltr\UpdateFeatureset;

/**
 * Class LtrNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class LtrNamespace extends AbstractNamespace
{
    /**
     * Add features to an existing feature set in the default feature store.
     *
     * $params['name']        = (string) The name of the feature set to add features to. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['merge']       = (boolean) Whether to merge the feature list or append only. (Default = false)
     * $params['routing']     = (string) Specific routing value.
     * $params['version']     = (integer) Version check to ensure feature set is modified with expected version.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function addFeaturesToSet(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');
        $store = $this->extractArgument($params, 'store');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(AddFeaturesToSet::class);
        $endpoint->setParams($params);
        $endpoint->setName($name);
        $endpoint->setStore($store);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Add features to an existing feature set in the default feature store.
     *
     * $params['name']        = (string) The name of the feature set to add features to. (Required)
     * $params['query']       = (string) Query string to filter existing features from the store by name. When provided, only features matching this query will be added to the feature set, and no request body should be included. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['merge']       = (boolean) Whether to merge the feature list or append only. (Default = false)
     * $params['routing']     = (string) Specific routing value.
     * $params['version']     = (integer) Version check to ensure feature set is modified with expected version.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function addFeaturesToSetByQuery(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');
        $query = $this->extractArgument($params, 'query');
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(AddFeaturesToSetByQuery::class);
        $endpoint->setParams($params);
        $endpoint->setName($name);
        $endpoint->setQuery($query);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves cache statistics for all feature stores.
     *
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * Create or update a feature in the default feature store.
     *
     * $params['id']          = (string) The name of the feature. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['routing']     = (string) Specific routing value.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createFeature(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(CreateFeature::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Create or update a feature set in the default feature store.
     *
     * $params['id']          = (string) The name of the feature set. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['routing']     = (string) Specific routing value.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createFeatureset(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(CreateFeatureset::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Create or update a model in the default feature store.
     *
     * $params['id']          = (string) The name of the model. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['routing']     = (string) Specific routing value.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createModel(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(CreateModel::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Create a model from an existing feature set in the default feature store.
     *
     * $params['name']        = (string) The name of the feature set to use for creating the model. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['routing']     = (string) Specific routing value.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createModelFromSet(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');
        $store = $this->extractArgument($params, 'store');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(CreateModelFromSet::class);
        $endpoint->setParams($params);
        $endpoint->setName($name);
        $endpoint->setStore($store);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a new feature store with the specified name.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * Delete a feature from the default feature store.
     *
     * $params['id']          = (string) The name of the feature. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteFeature(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteFeature::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Delete a feature set from the default feature store.
     *
     * $params['id']          = (string) The name of the feature set. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteFeatureset(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteFeatureset::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Delete a model from the default feature store.
     *
     * $params['id']          = (string) The name of the model. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteModel(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteModel::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Deletes a feature store with the specified name.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * Get a feature from the default feature store.
     *
     * $params['id']          = (string) The name of the feature. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getFeature(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(GetFeature::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Get a feature set from the default feature store.
     *
     * $params['id']          = (string) The name of the feature set. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getFeatureset(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(GetFeatureset::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Get a model from the default feature store.
     *
     * $params['id']          = (string) The name of the model. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getModel(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(GetModel::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Checks if a store exists.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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
     * Search for features in a feature store.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['from']        = (integer) The offset from the first result (for pagination). (Default = 0)
     * $params['prefix']      = (string) A name prefix to filter features by.
     * $params['size']        = (integer) The number of features to return. (Default = 20)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function searchFeatures(array $params = [])
    {
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(SearchFeatures::class);
        $endpoint->setParams($params);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Search for feature sets in a feature store.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['from']        = (integer) The offset from the first result (for pagination). (Default = 0)
     * $params['prefix']      = (string) A name prefix to filter feature sets by.
     * $params['size']        = (integer) The number of feature sets to return. (Default = 20)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function searchFeaturesets(array $params = [])
    {
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(SearchFeaturesets::class);
        $endpoint->setParams($params);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Search for models in a feature store.
     *
     * $params['store']       = (string) The name of the feature store.
     * $params['from']        = (integer) The offset from the first result (for pagination). (Default = 0)
     * $params['prefix']      = (string) A name prefix to filter models by.
     * $params['size']        = (integer) The number of models to return. (Default = 20)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function searchModels(array $params = [])
    {
        $store = $this->extractArgument($params, 'store');

        $endpoint = $this->endpointFactory->getEndpoint(SearchModels::class);
        $endpoint->setParams($params);
        $endpoint->setStore($store);

        return $this->performRequest($endpoint);
    }

    /**
     * Provides information about the current status of the LTR plugin.
     *
     * $params['node_id']     = (array) A comma-separated list of node IDs or names to limit the returned information; use `_local` to return information from the node you're connecting to, leave empty to get information from all nodes.
     * $params['stat']        = (array) A comma-separated list of stats to retrieve; use `_all` or empty string to retrieve all stats.
     * $params['timeout']     = (string) The time in milliseconds to wait for a response.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
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

    /**
     * Update a feature in the default feature store.
     *
     * $params['id']          = (string) The name of the feature. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['routing']     = (string) Specific routing value.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function updateFeature(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(UpdateFeature::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Update a feature set in the default feature store.
     *
     * $params['id']          = (string) The name of the feature set. (Required)
     * $params['store']       = (string) The name of the feature store.
     * $params['routing']     = (string) Specific routing value.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function updateFeatureset(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $store = $this->extractArgument($params, 'store');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(UpdateFeatureset::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setStore($store);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

}
