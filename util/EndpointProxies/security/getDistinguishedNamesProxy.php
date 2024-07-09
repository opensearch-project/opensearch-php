<?php

return <<<'EOD'

    /**
     * Retrieves distinguished names. Only accessible to super-admins and with rest-api permissions when enabled.
     * If 'cluster_name' is provided in $params, calls GetDistinguishedName.
     *
     * $params['cluster_name'] = (string) Name of the cluster.
     * $params['show_all']     = (boolean) Show all DN.
     * $params['pretty']       = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']        = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']  = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']       = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']  = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getDistinguishedNames(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        if (isset($params['cluster_name'])) {
            $endpoint = $endpointBuilder('Security\GetDistinguishedName');
            $cluster_name = $this->extractArgument($params, 'cluster_name');
            $endpoint->setClusterName($cluster_name);
        } else {
            $endpoint = $endpointBuilder('Security\GetDistinguishedNames');
        }
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
