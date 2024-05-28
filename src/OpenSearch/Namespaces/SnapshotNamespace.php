<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
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
 * Class SnapshotNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class SnapshotNamespace extends AbstractNamespace
{
    /**
     * Removes stale data from repository.
     *
     * $params['repository']              = (string) Snapshot repository to clean up. (Required)
     * $params['master_timeout']          = (string) Period to wait for a connection to the master node.
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['timeout']                 = (string) Period to wait for a response.
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function cleanupRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\CleanupRepository');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }
    /**
     * Clones indices from one snapshot into another snapshot in the same repository.
     *
     * $params['repository']              = (string) A repository name (Required)
     * $params['snapshot']                = (string) The name of the snapshot to clone from (Required)
     * $params['target_snapshot']         = (string) The name of the cloned snapshot to create (Required)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     * $params['body']                    = (array) The snapshot clone definition (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function clone(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');
        $target_snapshot = $this->extractArgument($params, 'target_snapshot');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\CloneSnapshot');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);
        $endpoint->setTargetSnapshot($target_snapshot);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * Creates a snapshot in a repository.
     *
     * $params['repository']              = (string) Repository for the snapshot. (Required)
     * $params['snapshot']                = (string) Name of the snapshot. Must be unique in the repository. (Required)
     * $params['master_timeout']          = (string) Period to wait for a connection to the master node. If no response is received before the timeout expires, the request fails and returns an error.
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['wait_for_completion']     = (boolean) If `true`, the request returns a response when the snapshot is complete. If `false`, the request returns a response when the snapshot initializes. (Default = false)
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     * $params['body']                    = (array) The snapshot definition
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function create(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\Create');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * Creates a repository.
     *
     * $params['repository']              = (string) A repository name (Required)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['timeout']                 = (string) Explicit operation timeout
     * $params['verify']                  = (boolean) Whether to verify the repository after creation
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     * $params['body']                    = (array) The repository definition (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\CreateRepository');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * Deletes a snapshot.
     *
     * $params['repository']              = (string) A repository name (Required)
     * $params['snapshot']                = (string) A comma-separated list of snapshot names (Required)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function delete(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\Delete');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);

        return $this->performRequest($endpoint);
    }
    /**
     * Deletes a repository.
     *
     * $params['repository']              = (array) Name of the snapshot repository to unregister. Wildcard (`*`) patterns are supported. (Required)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['timeout']                 = (string) Explicit operation timeout
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\DeleteRepository');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }
    /**
     * Returns information about a snapshot.
     *
     * $params['repository']              = (string) Comma-separated list of snapshot repository names used to limit the request. Wildcard (*) expressions are supported. (Required)
     * $params['snapshot']                = (array) Comma-separated list of snapshot names to retrieve. Also accepts wildcards (*). - To get information about all snapshots in a registered repository, use a wildcard (*) or _all. - To get information about any snapshots that are currently running, use _current. (Required)
     * $params['master_timeout']          = (string) Period to wait for a connection to the master node. If no response is received before the timeout expires, the request fails and returns an error.
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['ignore_unavailable']      = (boolean) If false, the request returns an error for any snapshots that are unavailable. (Default = false)
     * $params['verbose']                 = (boolean) If true, returns additional information about each snapshot such as the version of Opensearch which took the snapshot, the start and end times of the snapshot, and the number of shards snapshotted.
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function get(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\Get');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);

        return $this->performRequest($endpoint);
    }
    /**
     * Returns information about a repository.
     *
     * $params['repository']              = (array) A comma-separated list of repository names
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['local']                   = (boolean) Return local information, do not retrieve the state from cluster-manager node. (Default = false)
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\GetRepository');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }
    /**
     * Restores a snapshot.
     *
     * $params['repository']              = (string) A repository name (Required)
     * $params['snapshot']                = (string) A snapshot name (Required)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['wait_for_completion']     = (boolean) Should this request wait until the operation has completed before returning (Default = false)
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     * $params['body']                    = (array) Details of what to restore
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function restore(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\Restore');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * Returns information about the status of a snapshot.
     *
     * $params['repository']              = (string) A repository name
     * $params['snapshot']                = (array) A comma-separated list of snapshot names
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['ignore_unavailable']      = (boolean) Whether to ignore unavailable snapshots, defaults to false which means a SnapshotMissingException is thrown (Default = false)
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function status(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\Status');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);

        return $this->performRequest($endpoint);
    }
    /**
     * Verifies a repository.
     *
     * $params['repository']              = (string) A repository name (Required)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to master node
     * $params['cluster_manager_timeout'] = (string) Operation timeout for connection to cluster-manager node.
     * $params['timeout']                 = (string) Explicit operation timeout
     * $params['pretty']                  = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                   = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function verifyRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Snapshot\VerifyRepository');
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }
}
