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
 * Class RollupNamespace
 *
 */
class RollupNamespace extends AbstractNamespace
{
    /**
     * $params['id'] = (string) The ID of the job to delete
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function deleteJob(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\DeleteJob');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id'] = (string) The ID of the job(s) to fetch. Accepts glob patterns, or left blank for all jobs
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function getJobs(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\GetJobs');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id'] = (string) The ID of the index to check rollup capabilities on, or left blank for all jobs
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function getRollupCaps(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\GetRollupCaps');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['index'] = (string) The rollup index or index pattern to obtain rollup capabilities from.
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function getRollupIndexCaps(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\GetRollupIndexCaps');
        $endpoint->setParams($params);
        $endpoint->setIndex($index);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id']   = (string) The ID of the job to create
     * $params['body'] = (array) The job configuration (Required)
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function putJob(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\PutJob');
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['index']                  = (list) The indices or index-pattern(s) (containing rollup or regular data) that should be searched (Required)
     * $params['type']                   = DEPRECATED (string) The doc type inside the index
     * $params['typed_keys']             = (boolean) Specify whether aggregation and suggester names should be prefixed by their respective types in the response
     * $params['rest_total_hits_as_int'] = (boolean) Indicates whether hits.total should be rendered as an integer or an object in the rest search response (Default = false)
     * $params['body']                   = (array) The search request body (Required)
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function rollupSearch(array $params = [])
    {
        $index = $this->extractArgument($params, 'index');
        $type = $this->extractArgument($params, 'type');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\RollupSearch');
        $endpoint->setParams($params);
        $endpoint->setIndex($index);
        $endpoint->setType($type);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id'] = (string) The ID of the job to start
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function startJob(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\StartJob');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id']                  = (string) The ID of the job to stop
     * $params['wait_for_completion'] = (boolean) True if the API should block until the job has fully stopped, false if should be executed async. Defaults to false.
     * $params['timeout']             = (time) Block for (at maximum) the specified duration while waiting for the job to stop.  Defaults to 30s.
     *
     * @param array $params Associative array of parameters
     * @return array

     *
     * @note This API is EXPERIMENTAL and may be changed or removed completely in a future release
     *
     */
    public function stopJob(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Rollup\StopJob');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
}
