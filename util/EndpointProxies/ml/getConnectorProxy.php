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
        $connector_id = $this->extractArgument($params, 'connector_id');
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\GetConnector::class);
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setConnectorId($connector_id);

        return $this->performRequest($endpoint);
    }
EOD;
