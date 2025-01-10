<?php

return <<<'EOD'

    /**
     * $params['model_id']       = (string) The id of the model (Required)
     * $params['body']           = (string) The body of the request
     *
     * @param array $params Associative array of parameters
     *
     * @return array|\OpenSearch\Response
     *   The response.
     */
    public function deployModel(array $params = [])
    {
        $modelId = $this->extractArgument($params, 'model_id');
        $body = $this->extractArgument($params, 'body');
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\DeployModel::class);
        $endpoint->setParams($params);
        $endpoint->setModelId($modelId);
        if ($body) {
            $endpoint->setBody($body);
        }

        return $this->performRequest($endpoint);
    }
EOD;
