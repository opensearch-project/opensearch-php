<?php

return <<<'EOD'
    /**
     * $params['query'] = (string) The SQL Query
     * $params['format'] = (string) The response format
     * $params['cursor'] = (string) The cursor given by the server
     * $params['fetch_size'] = (int) The fetch size
     *
     * @param array{'query'?: string, 'cursor'?: string, 'fetch_size'?: int} $params Associative array of parameters
     * @return array
     *
     * Note: Use of `query`, `cursor` and `fetch_size` parameters is deprecated. Pass them in `body` instead.
     * 
     */
    public function query(array $params): array
    {
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Sql\Query::class);
        $body = $this->extractArgument($params, 'body') ?? [];
        $endpoint->setBody(array_merge($body, array_filter([
            'query' => $this->extractArgument($params, 'query'),
            'cursor' => $this->extractArgument($params, 'cursor'),
            'fetch_size' => $this->extractArgument($params, 'fetch_size'),
        ])));
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
