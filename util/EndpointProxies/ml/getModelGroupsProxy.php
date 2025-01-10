<?php

return <<<'EOD'

    /**
     * $params['body']             = (string) The body of the request
     *
     * @param array $params Associative array of parameters
     *
     * @return array|\OpenSearch\Response
     *   The response.
     */
    public function getModelGroups(array $params = [])
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
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\GetModelGroups::class);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
