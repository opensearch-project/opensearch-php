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
 * Class SlmNamespace
 *
 */
class SlmNamespace extends AbstractNamespace
{
    /**
     * $params['policy_id'] = (string) The id of the snapshot lifecycle policy to remove
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteLifecycle(array $params = [])
    {
        $policy_id = $this->extractArgument($params, 'policy_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Slm\DeleteLifecycle');
        $endpoint->setParams($params);
        $endpoint->setPolicyId($policy_id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['policy_id'] = (string) The id of the snapshot lifecycle policy to be executed
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function executeLifecycle(array $params = [])
    {
        $policy_id = $this->extractArgument($params, 'policy_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Slm\ExecuteLifecycle');
        $endpoint->setParams($params);
        $endpoint->setPolicyId($policy_id);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function executeRetention(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Slm\ExecuteRetention');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['policy_id'] = (list) Comma-separated list of snapshot lifecycle policies to retrieve
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getLifecycle(array $params = [])
    {
        $policy_id = $this->extractArgument($params, 'policy_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Slm\GetLifecycle');
        $endpoint->setParams($params);
        $endpoint->setPolicyId($policy_id);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getStats(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Slm\GetStats');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getStatus(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Slm\GetStatus');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['policy_id'] = (string) The id of the snapshot lifecycle policy
     * $params['body']      = (array) The snapshot lifecycle policy definition to register
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putLifecycle(array $params = [])
    {
        $policy_id = $this->extractArgument($params, 'policy_id');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Slm\PutLifecycle');
        $endpoint->setParams($params);
        $endpoint->setPolicyId($policy_id);
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
        $endpoint = $endpointBuilder('Slm\Start');
        $endpoint->setParams($params);

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
        $endpoint = $endpointBuilder('Slm\Stop');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
}
