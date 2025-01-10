<?php

return <<<'EOD'
    /**
     * $params['query'] = (string) The SQL Query
     *
     * @param array{'query': string} $params Associative array of parameters
     * @return array|\OpenSearch\Response
     *
     * Note: Use of query parameter is deprecated. Pass it in `body` instead.
     */
    public function explain(array $params)
    {
        $body = $this->extractArgument($params, 'body') ?? [];
        $query = $this->extractArgument($params, 'query');

        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Sql\Explain::class);
        $endpoint->setBody(array_merge($body, [
            'query' => $query,
        ]));
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
