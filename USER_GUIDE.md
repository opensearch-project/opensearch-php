- [User Guide](#user-guide)
  - [Example usage](#example-usage)
# User Guide

Install this client using Composer into your project `composer req opensearch-project/opensearch-php`

## Example usage

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$client = (new \OpenSearch\ClientBuilder())
    ->setHosts(['https://localhost:9200'])
    ->setBasicAuthentication('admin', 'admin') // For testing only. Don't store credentials in code.
    ->setSSLVerification(false) // For testing only. Use certificate for validation
    ->build();

$indexName = 'test-index-name';

// Print OpenSearch version information on console.
var_dump($client->info());

// Create an index with non-default settings.
$client->indices()->create([
    'index' => $indexName,
    'body' => [
        'settings' => [
            'index' => [
                'number_of_shards' => 4
            ]
        ]
    ]
]);

$client->create([
    'index' => $indexName,
    'id' => 1,
    'body' => [
        'title' => 'Moneyball',
        'director' => 'Bennett Miller',
        'year' => 2011
    ]
]);

// Search for it
var_dump(
    $client->search([
        'index' => $indexName,
        'body' => [
            'size' => 5,
            'query' => [
                'multi_match' => [
                    'query' => 'miller',
                    'fields' => ['title^2', 'director']
                ]
            ]
        ]
    ])
);

// Delete a single document
$client->delete([
    'index' => $indexName,
    'id' => 1,
]);


// Delete index
$client->indices()->delete([
    'index' => $indexName
]);
```
