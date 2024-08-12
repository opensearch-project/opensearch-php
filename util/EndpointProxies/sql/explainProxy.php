<?php

return <<<'EOD'
    /**
     * $params['query'] = (string) The SQL Query
     *
     * @param array{'query': string} $params Associative array of parameters
     * @return array
     *
     * Note: Use of query parameter is deprecated. Pass it in `body` instead.
     */
    public function explain(array $params): array
    {
        $endpointBuilder = $this->endpoints;

        $body = $this->extractArgument($params, 'body') ?? [];
        $query = $this->extractArgument($params, 'query');

        $endpoint = $endpointBuilder('Sql\Explain');
        $endpoint->setBody(array_merge($body, [
            'query' => $query,
        ]));
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
