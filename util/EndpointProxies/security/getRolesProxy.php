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
        $endpointBuilder = $this->endpoints;
        if (isset($params['role'])) {
            $endpoint = $endpointBuilder('Security\GetRole');
            $endpoint->setRole($params['role']);
            unset($params['role']);
        } else {
            $endpoint = $endpointBuilder('Security\GetRoles');
        }
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
