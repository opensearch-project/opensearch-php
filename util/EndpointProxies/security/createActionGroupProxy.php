<?php

return <<<'EOD'

    /**
     * Creates or replaces the specified action group.
     *
     * $params['action_group'] = (string) The name of the action group to create or replace. (Required)
     * $params['allowed_actions']    = (array) list of allowed actions
     * $params['pretty']       = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']        = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']  = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']       = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']  = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array|\OpenSearch\Response
     */
    public function createActionGroup(array $params = [])
    {
        $action_group = $this->extractArgument($params, 'action_group');
        $body = $this->extractArgument($params, 'body');
        if ($body ===null) {
            $body =[
                'allowed_actions' => $this->extractArgument($params, 'allowed_actions'),
            ];
        }
        
        $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Security\CreateActionGroup::class);
        $endpoint->setParams($params);
        $endpoint->setActionGroup($action_group);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
