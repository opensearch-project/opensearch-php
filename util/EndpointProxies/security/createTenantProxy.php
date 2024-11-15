<?php

return <<<'EOD'

    /**
     * Creates or replaces the specified tenant.
     *
     * $params['tenant']      = (string) The name of the tenant to create
     * $params['description'] = (string) Description of the tenant
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createTenant(array $params = [])
    {
        $tenant = $this->extractArgument($params, 'tenant');
        $body = $this->extractArgument($params, 'body');
        if ($body ===null) {
            $body = [
                'description' => $this->extractArgument($params, 'description'),
            ];
        }

        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Security\CreateTenant::class);
        $endpoint->setParams($params);
        $endpoint->setTenant($tenant);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
