<?php

return <<<'EOD'

    /**
     * $params['id']             = (string) The id of the model (Required)
     *
     * @param array $params Associative array of parameters
     *
     * @return array
     *   The response.
     */
    public function getModel(array $params = []): array
    {
        $id = $this->extractArgument($params, 'id');
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ml\GetModel');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
EOD;
