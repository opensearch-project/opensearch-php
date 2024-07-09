<?php

return <<<'EOD'

    /**
     * Creates or replaces the specified user.
     *
     * $params['username']    = (string)  (Required)
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     * $params['password']                   = (string)
     * $params['opendistro_security_roles']  = (array)
     * $params['backend_roles']              = (array)
     * $params['attributes']                 = (array)
     * 
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createUser(array $params = [])
    {
        $username = $this->extractArgument($params, 'username');
        $body = $this->extractArgument($params, 'body');
        if ($body ===null) {
            $body = array_filter([
                'password' => $this->extractArgument($params, 'password'),
                'opendistro_security_roles' => $this->extractArgument($params, 'opendistro_security_roles'),
                'backend_roles' => $this->extractArgument($params, 'backend_roles'),
                'attributes' => $this->extractArgument($params, 'attributes'),
            ]);
        }

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\CreateUser');
        $endpoint->setParams($params);
        $endpoint->setUsername($username);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
