<?php

return <<<'EOD'

    /**
     * Creates or replaces the specified role.
     *
     * $params['role']        = (string)  (Required)
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     * $params['cluster_permissions']  = (array)
     * $params['index_permissions']    = (array)
     * $params['tenant_permissions']   = (array)
     * 
     * @param array $params Associative array of parameters
     * @return array|\OpenSearch\Response
     */
    public function createRole(array $params = [])
    {
        $role = $this->extractArgument($params, 'role');
        $body = $this->extractArgument($params, 'body');
        if ($body ===null) {
            $body = array_filter([
                'cluster_permissions' => $this->extractArgument($params, 'cluster_permissions'),
                'index_permissions' => $this->extractArgument($params, 'index_permissions'),
                'tenant_permissions' => $this->extractArgument($params, 'tenant_permissions'),
            ]);
        }

        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Security\CreateRole::class);
        $endpoint->setParams($params);
        $endpoint->setRole($role);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
