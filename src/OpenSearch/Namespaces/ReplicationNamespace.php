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

use OpenSearch\Endpoints\Replication\AutofollowStats;
use OpenSearch\Endpoints\Replication\CreateReplicationRule;
use OpenSearch\Endpoints\Replication\DeleteReplicationRule;
use OpenSearch\Endpoints\Replication\FollowerStats;
use OpenSearch\Endpoints\Replication\LeaderStats;
use OpenSearch\Endpoints\Replication\Pause;
use OpenSearch\Endpoints\Replication\Resume;
use OpenSearch\Endpoints\Replication\Start;
use OpenSearch\Endpoints\Replication\Status;
use OpenSearch\Endpoints\Replication\Stop;
use OpenSearch\Endpoints\Replication\UpdateSettings;

/**
 * Class ReplicationNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class ReplicationNamespace extends AbstractNamespace
{
    /**
     * Retrieves information about any auto-follow activity and any replication rules configured on the specified cluster.
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
    public function autofollowStats(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(AutofollowStats::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Automatically starts the replication on indexes matching a specified pattern.
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
    public function createReplicationRule(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(CreateReplicationRule::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Deletes the specified replication rule.
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
    public function deleteReplicationRule(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteReplicationRule::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves information about any follower (syncing) indexes on a specified cluster.
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
    public function followerStats(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(FollowerStats::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves information about any replicated leader indexes on a specified cluster.
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
    public function leaderStats(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(LeaderStats::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Pauses the replication of the leader index.
     *
     * $params['index']       = (string) The name of the data stream, index, or index alias to perform bulk actions on.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function pause(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Pause::class);
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Resumes replication of the leader index.
     *
     * $params['index']       = (string) The name of the data stream, index, or index alias to perform bulk actions on.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function resume(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Resume::class);
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Initiates the replication of an index from the leader cluster to the follower cluster.
     *
     * $params['index']       = (string) The name of the data stream, index, or index alias to perform bulk actions on.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function start(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Start::class);
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves the the status of an index replication.
     *
     * $params['index']       = (string) The name of the data stream, index, or index alias to perform bulk actions on.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function status(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');

        $endpoint = $this->endpointFactory->getEndpoint(Status::class);
        $endpoint->setParams($params);
        $endpoint->setIndex($index);

        return $this->performRequest($endpoint);
    }

    /**
     * Terminates the replication and converts the follower index to a standard index.
     *
     * $params['index']       = (string) The name of the data stream, index, or index alias to perform bulk actions on.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function stop(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Stop::class);
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Updates any settings on the follower index.
     *
     * $params['index']       = (string) The name of the data stream, index, or index alias to perform bulk actions on.
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function updateSettings(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(UpdateSettings::class);
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

}
