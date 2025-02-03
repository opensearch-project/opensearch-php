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

use OpenSearch\Endpoints\Knn\DeleteModel;
use OpenSearch\Endpoints\Knn\GetModel;
use OpenSearch\Endpoints\Knn\SearchModels;
use OpenSearch\Endpoints\Knn\Stats;
use OpenSearch\Endpoints\Knn\TrainModel;
use OpenSearch\Endpoints\Knn\Warmup;

/**
 * Class KnnNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class KnnNamespace extends AbstractNamespace
{
    /**
     * Used to delete a particular model in the cluster.
     *
     * $params['model_id']    = (string) The id of the model.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteModel(array $params = [])
    {
        $model_id = $this->extractArgument($params, 'model_id');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteModel::class);
        $endpoint->setParams($params);
        $endpoint->setModelId($model_id);

        return $this->performRequest($endpoint);
    }

    /**
     * Used to retrieve information about models present in the cluster.
     *
     * $params['model_id']    = (string) The id of the model.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getModel(array $params = [])
    {
        $model_id = $this->extractArgument($params, 'model_id');

        $endpoint = $this->endpointFactory->getEndpoint(GetModel::class);
        $endpoint->setParams($params);
        $endpoint->setModelId($model_id);

        return $this->performRequest($endpoint);
    }

    /**
     * Use an OpenSearch query to search for models in the index.
     *
     * $params['_source']                       = (array) Set to `true` or `false` to return the `_source` field or not, or a list of fields to return.
     * $params['_source_excludes']              = (array) List of fields to exclude from the returned `_source` field.
     * $params['_source_includes']              = (array) List of fields to extract and return from the `_source` field.
     * $params['allow_no_indices']              = (boolean) Whether to ignore if a wildcard indexes expression resolves into no concrete indexes. (This includes `_all` string or when no indexes have been specified).
     * $params['allow_partial_search_results']  = (boolean) Indicate if an error should be returned if there is a partial search failure or timeout. (Default = true)
     * $params['analyze_wildcard']              = (boolean) Specify whether wildcard and prefix queries should be analyzed. (Default = false)
     * $params['analyzer']                      = (string) The analyzer to use for the query string.
     * $params['batched_reduce_size']           = (integer) The number of shard results that should be reduced at once on the coordinating node. This value should be used as a protection mechanism to reduce the memory overhead per search request if the potential number of shards in the request can be large. (Default = 512)
     * $params['ccs_minimize_roundtrips']       = (boolean) Indicates whether network round-trips should be minimized as part of cross-cluster search requests execution. (Default = true)
     * $params['default_operator']              = (enum) The default operator for query string query (AND or OR). (Options = AND,OR)
     * $params['df']                            = (string) The field to use as default where no field prefix is given in the query string.
     * $params['docvalue_fields']               = (array) Comma-separated list of fields to return as the docvalue representation of a field for each hit.
     * $params['expand_wildcards']              = (any) Whether to expand wildcard expression to concrete indexes that are open, closed or both.
     * $params['explain']                       = (boolean) Specify whether to return detailed information about score computation as part of a hit.
     * $params['from']                          = (integer) Starting offset. (Default = 0)
     * $params['ignore_throttled']              = (boolean) Whether specified concrete, expanded or aliased indexes should be ignored when throttled.
     * $params['ignore_unavailable']            = (boolean) Whether specified concrete indexes should be ignored when unavailable (missing or closed).
     * $params['lenient']                       = (boolean) Specify whether format-based query failures (such as providing text to a numeric field) should be ignored.
     * $params['max_concurrent_shard_requests'] = (integer) The number of concurrent shard requests per node this search executes concurrently. This value should be used to limit the impact of the search on the cluster in order to limit the number of concurrent shard requests. (Default = 5)
     * $params['pre_filter_shard_size']         = (integer) Threshold that enforces a pre-filter round-trip to prefilter search shards based on query rewriting if the number of shards the search request expands to exceeds the threshold. This filter round-trip can limit the number of shards significantly if for instance a shard can not match any documents based on its rewrite method, that is if date filters are mandatory to match but the shard bounds and the query are disjoint.
     * $params['preference']                    = (string) Specify the node or shard the operation should be performed on. (Default = random)
     * $params['q']                             = (string) Query in the Lucene query string syntax.
     * $params['request_cache']                 = (boolean) Specify if request cache should be used for this request or not, defaults to index level setting.
     * $params['rest_total_hits_as_int']        = (boolean) Indicates whether `hits.total` should be rendered as an integer or an object in the rest search response. (Default = false)
     * $params['routing']                       = (any) Comma-separated list of specific routing values.
     * $params['scroll']                        = (string) Specify how long a consistent view of the index should be maintained for scrolled search.
     * $params['search_type']                   = (enum) Search operation type. (Options = dfs_query_then_fetch,query_then_fetch)
     * $params['seq_no_primary_term']           = (boolean) Specify whether to return sequence number and primary term of the last modification of each hit.
     * $params['size']                          = (integer) Number of hits to return. (Default = 10)
     * $params['sort']                          = (array) Comma-separated list of <field>:<direction> pairs.
     * $params['stats']                         = (array) Specific 'tag' of the request for logging and statistical purposes.
     * $params['stored_fields']                 = (array) Comma-separated list of stored fields to return.
     * $params['suggest_field']                 = (string) Specify which field to use for suggestions.
     * $params['suggest_mode']                  = (enum) Specify suggest mode. (Options = always,missing,popular)
     * $params['suggest_size']                  = (integer) How many suggestions to return in response.
     * $params['suggest_text']                  = (string) The source text for which the suggestions should be returned.
     * $params['terminate_after']               = (integer) The maximum number of documents to collect for each shard, upon reaching which the query execution will terminate early.
     * $params['timeout']                       = (string) Operation timeout.
     * $params['track_scores']                  = (boolean) Whether to calculate and return scores even if they are not used for sorting.
     * $params['track_total_hits']              = (boolean) Indicate if the number of documents that match the query should be tracked.
     * $params['typed_keys']                    = (boolean) Specify whether aggregation and suggester names should be prefixed by their respective types in the response.
     * $params['version']                       = (boolean) Whether to return document version as part of a hit.
     * $params['pretty']                        = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']                         = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace']                   = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                        = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']                   = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function searchModels(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(SearchModels::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Provides information about the current status of the k-NN plugin.
     *
     * $params['node_id']     = (array) Comma-separated list of node IDs or names to limit the returned information; use `_local` to return information from the node you're connecting to, leave empty to get information from all nodes.
     * $params['stat']        = (array) Comma-separated list of stats to retrieve; use `_all` or empty string to retrieve all stats.
     * $params['timeout']     = (string) Operation timeout.
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

    /**
     * Create and train a model that can be used for initializing k-NN native library indexes during indexing.
     *
     * $params['model_id']    = (string) The id of the model.
     * $params['preference']  = (string) Preferred node to execute training.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function trainModel(array $params = [])
    {
        $model_id = $this->extractArgument($params, 'model_id');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(TrainModel::class);
        $endpoint->setParams($params);
        $endpoint->setModelId($model_id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Preloads native library files into memory, reducing initial search latency for specified indexes.
     *
     * $params['index']       = (array) Comma-separated list of indexes; use `_all` or empty string to perform the operation on all indexes. (Required)
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human readable values for statistics. (Default = true)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Used to reduce the response. This parameter takes a comma-separated list of filters. It supports using wildcards to match any field or part of a field’s name. You can also exclude fields with "-".
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function warmup(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');

        $endpoint = $this->endpointFactory->getEndpoint(Warmup::class);
        $endpoint->setParams($params);
        $endpoint->setIndex($index);

        return $this->performRequest($endpoint);
    }

}
