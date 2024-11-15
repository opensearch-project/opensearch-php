<?php

return <<<'EOD'
    /**
     * This API will be removed in a future version. Use 'close' API instead.
     * 
     * $params['cursor'] = (string) The cursor given by the server
     *
     * @param array{'cursor': string} $params Associative array of parameters
     * @return array
     */
    public function closeCursor(array $params): array
    {
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Sql\Close::class);
        $endpoint->setBody(array_filter([
            'cursor' => $this->extractArgument($params, 'cursor'),
        ]));
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
