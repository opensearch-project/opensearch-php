# Machine Learning Example Usage

Walks through the process of setting up a model to generate
vector embeddings from OpenAI on AWS managed Opensearch.

### Prerequisites

* This example assumes you are using the AWS managed OpenSearch
  service. See [The ml commons documentation](https://github.com/opensearch-project/ml-commons/blob/main/docs/remote_inference_blueprints/openai_connector_embedding_blueprint.md) for more examples and further information.
* You will need an API key from OpenAI. [Sign up](https://platform.openai.com/signup)
* The API key must be stored in [AWS Secrets Manager](https://aws.amazon.com/secrets-manager/)

```php
<?php

# Register a model group.
$modelGroupResponse = $client->ml()->registerModelGroup([
  'body' => [
    'name' => 'openai_model_group',
    'description' => 'Group containing models for OpenAI',
  ],
]);

# Create the connector.
$connectorResponse = $client->ml()->createConnector([
  'body' => [
    'name' => "Open AI Embedding Connector",
    'description' => "Creates a connector to Open AI's embedding endpoint",
    'version' => 1,
    'protocol' => 'http',
    'parameters' => ['model' => 'text-embedding-ada-002'],
    'credential' => [
      "secretArn" => '<Your Secret ARN from AWS Secrets Manager>',
      "roleArn" => '<Your IAM role ARN>',
    ]
    'actions' => [
      [
        'action_type' => 'predict',
        'method' => 'POST',
        'url' => 'https://api.openai.com/v1/embeddings',
        'headers' => [
          'Authorization': 'Bearer ${credential.secretArn.<Your Open AI Secret in Secrets Manager>}'
        ],
        'request_body' => "{ \"input\": \${parameters.input}, \"model\": \"\${parameters.model}\" }",
        'pre_process_function' => "connector.pre_process.openai.embedding",
        'post_process_function' => "connector.post_process.openai.embedding",
      ],
    ],
  ],
]);

# Register the model.
$registerModelResponse = $client->ml()->registerModel([
  'body' => [
    'name' => 'OpenAI embedding model',
    'function_name' => 'remote',
    'model_group_id' => $modelGroupResponse['model_group_id'],
    'description' => 'Model for retrieving vector embeddings from OpenAI',
    'connector_id' => $connectorResponse['connector_id'],
  ],
]);

# Monitor the state of the register model task.
$taskResponse = $client->ml()->getTask(['id' => $registerModelResponse['task_id']]);

assert($taskResponse['state'] === 'COMPLETED');

# Finally deploy the model. You will now be able to generate vector
# embeddings from OpenSearch (via OpenAI).
$client->ml()->deployModel(['id' => $taskResponse['model_id']]);
```
