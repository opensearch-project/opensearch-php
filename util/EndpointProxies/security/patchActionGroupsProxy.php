<?php

return <<<'EOD'

    /**
     * Creates, updates, or deletes multiple action groups in a single call.
     * If 'action_group' is provided in $params, calls patchActionGroup.
     *
     * $params['pretty']      = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']       = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) Comma-separated list of filters used to reduce the response.
     *
     * @param array $params Associative array of parameters
     * @return array|\OpenSearch\Response
     */
    public function patchActionGroups(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        if ($body ===null) {
            $body = $this->extractArgument($params, 'ops') ?? [];
        }

        if (isset($params['action_group'])) {
            $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Security\PatchActionGroup::class);
            $action_group = $this->extractArgument($params, 'action_group');
            $endpoint->setActionGroup($action_group);
        } else { 
            $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Security\PatchActionGroups::class);
        }
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
