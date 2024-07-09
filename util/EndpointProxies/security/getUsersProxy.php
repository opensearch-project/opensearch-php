<?php

return <<<'EOD'

    /**
     * Retrieve all internal users.
     * If 'username' is provided in $params, calls 'getUser'.
     *
     * $params['username']    = (string) The username of the user to fetch, omit to fetch all (optional).
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getUsers(array $params = []): array
    {
        $endpointBuilder = $this->endpoints;

        if (isset($params['username'])) {
            $endpoint = $endpointBuilder('Security\GetUser');
            $username = $this->extractArgument($params, 'username');
            $endpoint->setUsername($username);
        } else {
            $endpoint = $endpointBuilder('Security\GetUsers');
        }

        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
EOD;
