<?php

return <<<'EOD'

    /**
     * $params['body']             = (string) The body of the request
     *
     * @param array $params Associative array of parameters
     *
     * @return array
     *   The response.
     */
    public function getConnectors(array $params = []): array
    {
        if (!isset($params['body'])) {
            $params['body'] = [
              'query' => [
                'match_all' => new \StdClass(),
              ],
              'size' => 1000,
            ];
        }
        $body = $this->extractArgument($params, 'body');
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\GetConnectors::class);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
