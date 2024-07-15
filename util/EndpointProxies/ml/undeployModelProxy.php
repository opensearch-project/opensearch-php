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
    public function undeployModel(array $params = []): array
    {
        $id = $this->extractArgument($params, 'id');
        $body = $this->extractArgument($params, 'body');
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ml\UndeployModel');
        $endpoint->setParams($params);
        $endpoint->setId($id);
        if ($body) {
            $endpoint->setBody($body);
        }

        return $this->performRequest($endpoint);
    }
EOD;
