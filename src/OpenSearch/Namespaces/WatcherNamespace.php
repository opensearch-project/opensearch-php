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
 * Class WatcherNamespace
 *
 */
class WatcherNamespace extends AbstractNamespace
{
    /**
     * $params['watch_id']  = (string) Watch ID (Required)
     * $params['action_id'] = (list) A comma-separated list of the action ids to be acked
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function ackWatch(array $params = [])
    {
        $watch_id = $this->extractArgument($params, 'watch_id');
        $action_id = $this->extractArgument($params, 'action_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\AckWatch');
        $endpoint->setParams($params);
        $endpoint->setWatchId($watch_id);
        $endpoint->setActionId($action_id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['watch_id'] = (string) Watch ID
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function activateWatch(array $params = [])
    {
        $watch_id = $this->extractArgument($params, 'watch_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\ActivateWatch');
        $endpoint->setParams($params);
        $endpoint->setWatchId($watch_id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['watch_id'] = (string) Watch ID
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deactivateWatch(array $params = [])
    {
        $watch_id = $this->extractArgument($params, 'watch_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\DeactivateWatch');
        $endpoint->setParams($params);
        $endpoint->setWatchId($watch_id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id'] = (string) Watch ID
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteWatch(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\DeleteWatch');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id']    = (string) Watch ID
     * $params['debug'] = (boolean) indicates whether the watch should execute in debug mode
     * $params['body']  = (array) Execution control
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function executeWatch(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\ExecuteWatch');
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id'] = (string) Watch ID
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getWatch(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\GetWatch');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id']              = (string) Watch ID
     * $params['active']          = (boolean) Specify whether the watch is in/active by default
     * $params['version']         = (number) Explicit version number for concurrency control
     * $params['if_seq_no']       = (number) only update the watch if the last operation that has changed the watch has the specified sequence number
     * $params['if_primary_term'] = (number) only update the watch if the last operation that has changed the watch has the specified primary term
     * $params['body']            = (array) The watch
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putWatch(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\PutWatch');
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function start(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\Start');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['metric']           = (list) Controls what additional stat metrics should be include in the response
     * $params['emit_stacktraces'] = (boolean) Emits stack traces of currently running watches
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function stats(array $params = [])
    {
        $metric = $this->extractArgument($params, 'metric');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\Stats');
        $endpoint->setParams($params);
        $endpoint->setMetric($metric);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function stop(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Watcher\Stop');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
}
