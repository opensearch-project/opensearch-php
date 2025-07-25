<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */

namespace OpenSearch\Namespaces;

use OpenSearch\Endpoints\FlowFramework\Create;
use OpenSearch\Endpoints\FlowFramework\Delete;
use OpenSearch\Endpoints\FlowFramework\Deprovision;
use OpenSearch\Endpoints\FlowFramework\Get;
use OpenSearch\Endpoints\FlowFramework\GetStatus;
use OpenSearch\Endpoints\FlowFramework\GetSteps;
use OpenSearch\Endpoints\FlowFramework\Provision;
use OpenSearch\Endpoints\FlowFramework\Search;
use OpenSearch\Endpoints\FlowFramework\SearchState;
use OpenSearch\Endpoints\FlowFramework\Update;

/**
 * Class FlowFrameworkNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class FlowFrameworkNamespace extends AbstractNamespace
{
    /**
     * Creates a new workflow template.
     *
     * $params['provision']     = (boolean)  (Default = false)
     * $params['reprovision']   = (boolean)  (Default = false)
     * $params['update_fields'] = (boolean)  (Default = false)
     * $params['use_case']      = (string) Specifies the workflow template to use.
     * $params['validation']    = (string)  (Default = all)
     * $params['pretty']        = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']         = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']   = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']        = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']   = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function create(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Create::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Deletes a workflow template.
     *
     * $params['workflow_id']  = (string)
     * $params['clear_status'] = (boolean)  (Default = false)
     * $params['pretty']       = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']        = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']  = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']       = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']  = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function delete(array $params = [])
    {
        $workflow_id = $this->extractArgument($params, 'workflow_id');

        $endpoint = $this->endpointFactory->getEndpoint(Delete::class);
        $endpoint->setParams($params);
        $endpoint->setWorkflowId($workflow_id);

        return $this->performRequest($endpoint);
    }

    /**
     * Deprovision workflow's resources when you no longer need them.
     *
     * $params['workflow_id']  = (string)
     * $params['allow_delete'] = (string)
     * $params['pretty']       = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']        = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']  = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']       = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']  = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deprovision(array $params = [])
    {
        $workflow_id = $this->extractArgument($params, 'workflow_id');

        $endpoint = $this->endpointFactory->getEndpoint(Deprovision::class);
        $endpoint->setParams($params);
        $endpoint->setWorkflowId($workflow_id);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves a workflow template.
     *
     * $params['workflow_id'] = (string)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function get(array $params = [])
    {
        $workflow_id = $this->extractArgument($params, 'workflow_id');

        $endpoint = $this->endpointFactory->getEndpoint(Get::class);
        $endpoint->setParams($params);
        $endpoint->setWorkflowId($workflow_id);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves the current workflow provisioning status.
     *
     * $params['workflow_id'] = (string)
     * $params['all']         = (boolean) Whether to return all fields in the response. (Default = false)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getStatus(array $params = [])
    {
        $workflow_id = $this->extractArgument($params, 'workflow_id');

        $endpoint = $this->endpointFactory->getEndpoint(GetStatus::class);
        $endpoint->setParams($params);
        $endpoint->setWorkflowId($workflow_id);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves available workflow steps.
     *
     * $params['workflow_step'] = (string)
     * $params['pretty']        = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']         = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']   = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']        = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']   = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getSteps(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(GetSteps::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Provisioning a workflow. This API is also executed when the Create or Update Workflow API is called with the provision parameter set to true.
     *
     * $params['workflow_id'] = (string)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function provision(array $params = [])
    {
        $workflow_id = $this->extractArgument($params, 'workflow_id');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Provision::class);
        $endpoint->setParams($params);
        $endpoint->setWorkflowId($workflow_id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Search for workflows by using a query matching a field.
     *
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function search(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Search::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Search for workflows by using a query matching a field.
     *
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function searchState(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(SearchState::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Updates a workflow template that has not been provisioned.
     *
     * $params['workflow_id']   = (string)
     * $params['provision']     = (boolean)  (Default = false)
     * $params['reprovision']   = (boolean)  (Default = false)
     * $params['update_fields'] = (boolean)  (Default = false)
     * $params['use_case']      = (string) Specifies the workflow template to use.
     * $params['validation']    = (string)  (Default = all)
     * $params['pretty']        = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']         = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace']   = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']        = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']   = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function update(array $params = [])
    {
        $workflow_id = $this->extractArgument($params, 'workflow_id');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(Update::class);
        $endpoint->setParams($params);
        $endpoint->setWorkflowId($workflow_id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

}
