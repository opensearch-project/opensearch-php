<?php

return <<<'EOD'

    /**
     * $params['id']             = (string) The id of the connector (Required)
     *
     * @param array $params Associative array of parameters
     *
     * @return array
     *   The response.
     */
    public function getConnector(array $params = []): array
    {
        $id = $this->extractArgument($params, 'id');
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ml\GetConnector');
        $endpoint->setParams($params);
        $endpoint->setId($id);

        return $this->performRequest($endpoint);
    }
EOD;
