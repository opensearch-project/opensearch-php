<?php

return <<<'EOD'

    /**
     * $params['id']             = (string) The id of the model (Required)
     * $params['body']           = (string) The body of the request
     *
     * @param array $params Associative array of parameters
     *
     * @return array
     *   The response.
     */
    public function predict(array $params = []): array
    {
        $id = $this->extractArgument($params, 'id');
        $body = $this->extractArgument($params, 'body');
        $algorithm_name = $this->extractArgument($params, 'algorithm_name');
        $model_id = $this->extractArgument($params, 'model_id');
        
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\Predict::class);
        $endpoint->setParams($params)
            ->setId($id)
            ->setBody($body)
            ->setAlgorithmName($algorithm_name)
            ->setModelId($model_id);

        return $this->performRequest($endpoint);
    }
EOD;
