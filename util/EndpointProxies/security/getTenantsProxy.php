<?php

return <<<'EOD'

    /**
     * Retrieves tenants. Only accessible to super-admins and with rest-api permissions when enabled.
     * If 'tenant' is provided in $params, calls GetTenant.
     *
     * $params['tenant']      = (string) Name of the tenant.
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getTenants(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        if (isset($params['tenant'])) {
            $endpoint = $endpointBuilder('Security\GetTenant');
            $endpoint->setTenant($params['tenant']);
            unset($params['tenant']);
        } else {
            $endpoint = $endpointBuilder('Security\GetTenants');
        }
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
