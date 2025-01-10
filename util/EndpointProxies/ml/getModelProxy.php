<?php

return <<<'EOD'

    /**
     * $params['id']             = (string) The id of the model (Required)
     *
     * @param array $params Associative array of parameters
     *
     * @return array|\OpenSearch\Response
     *   The response.
     */
    public function getModel(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $model_id = $this->extractArgument($params, 'model_id');
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\GetModel::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setModelId($model_id);

        return $this->performRequest($endpoint);
    }
EOD;
