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
 * Class DanglingIndicesNamespace
 *
 */
class DanglingIndicesNamespace extends AbstractNamespace
{
    /**
     * $params['index_uuid']       = (string) The UUID of the dangling index
     * $params['accept_data_loss'] = (boolean) Must be set to true in order to delete the dangling index
     * $params['timeout']          = (time) Explicit operation timeout
     * $params['master_timeout']   = (time) Specify timeout for connection to master
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteDanglingIndex(array $params = [])
    {
        $index_uuid = $this->extractArgument($params, 'index_uuid');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('DanglingIndices\DeleteDanglingIndex');
        $endpoint->setParams($params);
        $endpoint->setIndexUuid($index_uuid);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['index_uuid']       = (string) The UUID of the dangling index
     * $params['accept_data_loss'] = (boolean) Must be set to true in order to import the dangling index
     * $params['timeout']          = (time) Explicit operation timeout
     * $params['master_timeout']   = (time) Specify timeout for connection to master
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function importDanglingIndex(array $params = [])
    {
        $index_uuid = $this->extractArgument($params, 'index_uuid');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('DanglingIndices\ImportDanglingIndex');
        $endpoint->setParams($params);
        $endpoint->setIndexUuid($index_uuid);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function listDanglingIndices(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('DanglingIndices\ListDanglingIndices');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
}
