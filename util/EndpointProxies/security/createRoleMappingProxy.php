<?php

return <<<'EOD'

/**
 * Creates or replaces the specified role mapping.
 *
 * $params['role']        = (string)  (Required)
 * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
 * $params['human']       = (boolean) Whether to return human readable values for statistics.
 * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
 * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
 * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
 * $params['backend_roles']  = (array)
 * $params['hosts']          = (array)
 * $params['users']          = (array)
 * 
 * @param array $params Associative array of parameters
 * @return array
 */
public function createRoleMapping(array $params = [])
{
    $role = $this->extractArgument($params, 'role');
    $body = $this->extractArgument($params, 'body');
    if ($body ===null) {
        $body = array_filter([
            'backend_roles' => $this->extractArgument($params, 'backend_roles'),
            'hosts' => $this->extractArgument($params, 'hosts'),
            'users' => $this->extractArgument($params, 'users'),
        ]);
    }

    $endpointBuilder = $this->endpoints;
    $endpoint = $endpointBuilder('Security\CreateRoleMapping');
    $endpoint->setParams($params);
    $endpoint->setRole($role);
    $endpoint->setBody($body);

    return $this->performRequest($endpoint);
}
EOD;
