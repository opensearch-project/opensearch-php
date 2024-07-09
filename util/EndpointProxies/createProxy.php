<?php

return <<<'EOD'

/**
     * Creates a new document in the index.Returns a 409 response when a document with a same ID already exists in the index.
     *
     * $params['id']                     = (string) Unique identifier for the document. (Required)
     * $params['index']                  = (string) Name of the data stream or index to target. If the target doesn’t exist and matches the name or wildcard (`*`) pattern of an index template with a `data_stream` definition, this request creates the data stream. If the target doesn’t exist and doesn’t match a data stream template, this request creates the index. (Required)
     * $params['pipeline']               = (string) ID of the pipeline to use to preprocess incoming documents.If the index has a default ingest pipeline specified, then setting the value to `_none` disables the default ingest pipeline for this request.If a final pipeline is configured it will always run, regardless of the value of this parameter.
     * $params['refresh']                = (enum) If `true`, OpenSearch refreshes the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` do nothing with refreshes.Valid values: `true`, `false`, `wait_for`. (Options = true,false,wait_for)
     * $params['routing']                = (string) Custom value used to route operations to a specific shard.
     * $params['timeout']                = (string) Period the request waits for the following operations: automatic index creation, dynamic mapping updates, waiting for active shards.
     * $params['version']                = (number) Explicit version number for concurrency control.The specified version must match the current version of the document for the request to succeed.
     * $params['version_type']           = (enum) Specific version type: `external`, `external_gte`. (Options = internal,external,external_gte,force)
     * $params['wait_for_active_shards'] = (any) The number of shard copies that must be active before proceeding with the operation.Set to `all` or any positive integer up to the total number of shards in the index (`number_of_replicas+1`).
     * $params['pretty']                 = (boolean) Whether to pretty format the returned JSON response.
     * $params['human']                  = (boolean) Whether to return human readable values for statistics.
     * $params['error_trace']            = (boolean) Whether to include the stack trace of returned errors.
     * $params['source']                 = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path']            = (any) Comma-separated list of filters used to reduce the response.
     * $params['body']                   = (array) The document (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function create(array $params = [])
    {
        $id = $this->extractArgument($params, 'id');
        $index = $this->extractArgument($params, 'index');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $id ? $endpointBuilder('Create') : $endpointBuilder('Index');
        $endpoint->setParams($params);
        $endpoint->setId($id);
        $endpoint->setIndex($index);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
EOD;
