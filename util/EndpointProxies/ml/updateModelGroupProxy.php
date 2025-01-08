<?php

return <<<'EOD'

    /**
     * $params['id']             = (string) The id of the model group (Required)
     * $params['body']           = (array) The body of the request (Required)
     *
     * @param array $params Associative array of parameters
     *
     * @return array
     *   The response.
     */
    public function updateModelGroup(array $params = []): array
    {
        $id = $this->extractArgument($params, 'id');
        $model_group_id = $this->extractArgument($params, 'model_group_id');
        $body = $this->extractArgument($params, 'body');
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\UpdateModelGroup::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);
        $endpoint->setId($id);
        $endpoint->setModelGroupId($model_group_id);

        return $this->performRequest($endpoint);
    }
EOD;
