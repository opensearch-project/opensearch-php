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
 * Class LicenseNamespace
 *
 */
class LicenseNamespace extends AbstractNamespace
{
    public function delete(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('License\Delete');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['local']             = (boolean) Return local information, do not retrieve the state from master node (default: false)
     * $params['accept_enterprise'] = (boolean) If the active license is an enterprise license, return type as 'enterprise' (default: false)
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/master/get-license.html
     */
    public function get(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('License\Get');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/master/get-basic-status.html
     */
    public function getBasicStatus(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('License\GetBasicStatus');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/master/get-trial-status.html
     */
    public function getTrialStatus(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('License\GetTrialStatus');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['acknowledge'] = (boolean) whether the user has acknowledged acknowledge messages (default: false)
     * $params['body']        = (array) licenses to be installed
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/master/update-license.html
     */
    public function post(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('License\Post');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['acknowledge'] = (boolean) whether the user has acknowledged acknowledge messages (default: false)
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/master/start-basic.html
     */
    public function postStartBasic(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('License\PostStartBasic');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['type']        = (string) The type of trial license to generate (default: "trial")
     * $params['acknowledge'] = (boolean) whether the user has acknowledged acknowledge messages (default: false)
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/master/start-trial.html
     */
    public function postStartTrial(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('License\PostStartTrial');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
}
