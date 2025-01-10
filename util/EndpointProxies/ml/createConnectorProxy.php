<?php

return <<<'EOD'

    /**
     * $params['body']             = (string) The body of the request (Required)
     *
     * @param array $params Associative array of parameters
     *
     * @return array|\OpenSearch\Response
     *   The response.
     */
    public function createConnector(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Ml\CreateConnector::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
