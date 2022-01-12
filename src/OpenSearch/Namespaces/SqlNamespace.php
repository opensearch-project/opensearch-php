<?php

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

use OpenSearch\Endpoints\AbstractEndpoint;
use function array_filter;

class SqlNamespace extends AbstractNamespace
{
    /**
     * $params['query'] = (string) The SQL Query
     * $params['cursor'] = (string) The cursor given by the server
     * $params['fetch_size'] = (int) The fetch size
     *
     * @param array{'query'?: string, 'cursor'?: string, 'fetch_size'?: int} $params Associative array of parameters
     * @return array
     */
    public function query(array $params): array
    {
        $endpointBuilder = $this->endpoints;

        /** @var AbstractEndpoint $endpoint */
        $endpoint = $endpointBuilder('Sql\Query');
        $endpoint->setBody(array_filter([
            'query' => $this->extractArgument($params, 'query'),
            'cursor' => $this->extractArgument($params, 'cursor'),
            'fetch_size' => $this->extractArgument($params, 'fetch_size'),
        ]));
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * $params['query'] = (string) The SQL Query
     *
     * @param array{'query': string} $params Associative array of parameters
     * @return array
     */
    public function explain(array $params): array
    {
        $endpointBuilder = $this->endpoints;

        $query = $this->extractArgument($params, 'query');

        /** @var AbstractEndpoint $endpoint */
        $endpoint = $endpointBuilder('Sql\Explain');
        $endpoint->setBody([
            'query' => $query,
        ]);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * $params['cursor'] = (string) The cursor given by the server
     *
     * @param array{'cursor': string} $params Associative array of parameters
     * @return array
     */
    public function closeCursor(array $params): array
    {
        $endpointBuilder = $this->endpoints;

        /** @var AbstractEndpoint $endpoint */
        $endpoint = $endpointBuilder('Sql\CursorClose');
        $endpoint->setBody(array_filter([
            'cursor' => $this->extractArgument($params, 'cursor'),
        ]));
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
}
