<?php

return <<<'EOD'

    /**
     * Retrieves roles. Only accessible to super-admins and with rest-api permissions when enabled.
     * If 'role' is provided in $params, calls getRole.
     *
     * $params['role']        = (string) Name of the role.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getRoles(array $params = [])
    {
        if (isset($params['role'])) {
            $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Security\GetRole::class);
            $role = $this->extractArgument($params, 'role');
            $endpoint->setRole($role);
        } else {
            $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Security\GetRoles::class);
        }
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
