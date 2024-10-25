<?php

return <<<'EOD'

    /**
     * $params['model_id']       = (string) The id of the model (Required)
     * $params['body']           = (string) The body of the request
     *
     * @param array $params Associative array of parameters
     *
     * @return array
     *   The response.
     */
    public function deployModel(array $params = []): array
    {
        $modelId = $this->extractArgument($params, 'model_id');
        $body = $this->extractArgument($params, 'body');
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ml\DeployModel');
        $endpoint->setParams($params);
        $endpoint->setModelId($modelId);
        if ($body) {
            $endpoint->setBody($body);
        }

        return $this->performRequest($endpoint);
    }
EOD;
