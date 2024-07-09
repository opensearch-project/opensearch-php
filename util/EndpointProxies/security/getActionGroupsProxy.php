<?php

return <<<'EOD'

    /**
     * Retrieves all action groups.
     * If 'action_group' is provided in $params, calls 'getActionGroup'.
     *
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getActionGroups(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        if (isset($params['action_group'])) {
            $endpoint = $endpointBuilder('Security\GetActionGroup');
            $action_group = $this->extractArgument($params, 'action_group');
            $endpoint->setActionGroup($action_group);
        } else {
            $endpoint = $endpointBuilder('Security\GetActionGroups');
        }
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
