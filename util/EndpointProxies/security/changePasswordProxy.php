<?php

return <<<'EOD'

    /**
     * Changes the password for the current user.
     *
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     * $params['current_password']   = (string) The current password
     * $params['password']           = (string) New password
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function changePassword(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');
        if ($body ===null) {
            $body =[
                'current_password' => $this->extractArgument($params, 'current_password'),
                'password' => $this->extractArgument($params, 'password'),
            ];
        }

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\ChangePassword');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
