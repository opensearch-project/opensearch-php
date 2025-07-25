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

use OpenSearch\Endpoints\Snapshot\CleanupRepository;
use OpenSearch\Endpoints\Snapshot\CloneSnapshot;
use OpenSearch\Endpoints\Snapshot\Create;
use OpenSearch\Endpoints\Snapshot\CreateRepository;
use OpenSearch\Endpoints\Snapshot\Delete;
use OpenSearch\Endpoints\Snapshot\DeleteRepository;
use OpenSearch\Endpoints\Snapshot\Get;
use OpenSearch\Endpoints\Snapshot\GetRepository;
use OpenSearch\Endpoints\Snapshot\Restore;
use OpenSearch\Endpoints\Snapshot\Status;
use OpenSearch\Endpoints\Snapshot\VerifyRepository;

/**
 * Class SnapshotNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class SnapshotNamespace extends AbstractNamespace
{
    /**
     * Removes any stale data from a snapshot repository.
     *
     * $params['repository']              = (string) Snapshot repository to clean up.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Period to wait for a connection to the cluster-manager node.
     * $params['timeout']                 = (string) The amount of time to wait for a response.
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function cleanupRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpoint = $this->endpointFactory->getEndpoint(CleanupRepository::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a clone of all or part of a snapshot in the same repository as the original snapshot.
     *
     * $params['repository']              = (string) The name of repository which will contain the snapshots clone.
     * $params['snapshot']                = (string) The name of the original snapshot.
     * $params['target_snapshot']         = (string) The name of the cloned snapshot.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     * $params['body']                    = (array) The snapshot clone definition. (Required)
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

        $endpoint = $this->endpointFactory->getEndpoint(CloneSnapshot::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);
        $endpoint->setTargetSnapshot($target_snapshot);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a snapshot within an existing repository.
     *
     * $params['repository']              = (string) The name of the repository where the snapshot will be stored.
     * $params['snapshot']                = (string) The name of the snapshot. Must be unique in the repository.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Period to wait for a connection to the cluster-manager node. If no response is received before the timeout expires, the request fails and returns an error.
     * $params['wait_for_completion']     = (boolean) When `true`, the request returns a response when the snapshot is complete. When `false`, the request returns a response when the snapshot initializes. (Default = false)
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     * $params['body']                    = (array) The snapshot definition.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function create(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Create::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Creates a snapshot repository.
     *
     * $params['repository']              = (string) The name for the newly registered repository.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['timeout']                 = (string) The amount of time to wait for a response.
     * $params['verify']                  = (boolean) When `true`, verifies the creation of the snapshot repository.
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     * $params['body']                    = (array) The repository definition. (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(CreateRepository::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Deletes a snapshot.
     *
     * $params['repository']              = (string) The name of the snapshot repository to delete.
     * $params['snapshot']                = (string) A comma-separated list of snapshot names to delete from the repository.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function delete(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');

        $endpoint = $this->endpointFactory->getEndpoint(Delete::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);

        return $this->performRequest($endpoint);
    }

    /**
     * Deletes a snapshot repository.
     *
     * $params['repository']              = (array) The name of the snapshot repository to unregister. Wildcard (`*`) patterns are supported.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['timeout']                 = (string) The amount of time to wait for a response.
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteRepository::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }

    /**
     * Returns information about a snapshot.
     *
     * $params['repository']              = (string) A comma-separated list of snapshot repository names used to limit the request. Wildcard (*) expressions are supported.
     * $params['snapshot']                = (array) A comma-separated list of snapshot names to retrieve. Also accepts wildcard expressions. (`*`). - To get information about all snapshots in a registered repository, use a wildcard (`*`) or `_all`. - To get information about any snapshots that are currently running, use `_current`.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['ignore_unavailable']      = (boolean) When `false`, the request returns an error for any snapshots that are unavailable. (Default = false)
     * $params['master_timeout']          = (string) Period to wait for a connection to the cluster-manager node. If no response is received before the timeout expires, the request fails and returns an error.
     * $params['verbose']                 = (boolean) When `true`, returns additional information about each snapshot, such as the version of OpenSearch which took the snapshot, the start and end times of the snapshot, and the number of shards contained in the snapshot. When `false`, returns only snapshot names and contained indexes. This is useful when the snapshots belong to a cloud-based repository, where each blob read is a cost or performance concern.
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function get(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');

        $endpoint = $this->endpointFactory->getEndpoint(Get::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);

        return $this->performRequest($endpoint);
    }

    /**
     * Returns information about a snapshot repository.
     *
     * $params['repository']              = (array) A comma-separated list of repository names.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['local']                   = (boolean) Whether to get information from the local node. (Default = false)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpoint = $this->endpointFactory->getEndpoint(GetRepository::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }

    /**
     * Restores a snapshot.
     *
     * $params['repository']              = (string) The name of the repository containing the snapshot
     * $params['snapshot']                = (string) The name of the snapshot to restore.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['wait_for_completion']     = (boolean) -| Whether to return a response after the restore operation has completed. When `false`, the request returns a response when the restore operation initializes. When `true`, the request returns a response when the restore operation completes. (Default = false)
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     * $params['body']                    = (array) Determines which settings and indexes to restore when restoring a snapshot
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function restore(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Restore::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Returns information about the status of a snapshot.
     *
     * $params['repository']              = (string) The name of the repository containing the snapshot.
     * $params['snapshot']                = (array) A comma-separated list of snapshot names.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['ignore_unavailable']      = (boolean) Whether to ignore any unavailable snapshots, When `false`, a `SnapshotMissingException` is thrown. (Default = false)
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function status(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');
        $snapshot = $this->extractArgument($params, 'snapshot');

        $endpoint = $this->endpointFactory->getEndpoint(Status::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);
        $endpoint->setSnapshot($snapshot);

        return $this->performRequest($endpoint);
    }

    /**
     * Verifies a repository.
     *
     * $params['repository']              = (string) The name of the repository containing the snapshot.
     * $params['cluster_manager_timeout'] = (string) The amount of time to wait for a response from the cluster manager node. For more information about supported time units, see [Common parameters](https://opensearch.org/docs/latest/api-reference/common-parameters/#time-units).
     * $params['master_timeout']          = (string) Explicit operation timeout for connection to cluster-manager node
     * $params['timeout']                 = (string) The amount of time to wait for a response.
     * $params['pretty']                  = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']                   = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']             = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']                  = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']             = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function verifyRepository(array $params = [])
    {
        $repository = $this->extractArgument($params, 'repository');

        $endpoint = $this->endpointFactory->getEndpoint(VerifyRepository::class);
        $endpoint->setParams($params);
        $endpoint->setRepository($repository);

        return $this->performRequest($endpoint);
    }

}
