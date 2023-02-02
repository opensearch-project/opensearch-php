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
    // or, if using AWS SigV4 authentication:
    ->setSigV4Region('us-east-2')
    ->setSigV4CredentialProvider(true)
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

// Create a document passing the id
$client->create([
    'index' => $indexName,
    'id' => 1,
    'body' => [
        'title' => 'Moneyball',
        'director' => 'Bennett Miller',
        'year' => 2011
    ]
]);

// Create a document without passing the id (will be generated automatically)
$client->create([
    'index' => $indexName,
    'body' => [
        'title' => 'Remember the Titans',
        'director' => 'Boaz Yakin',
        'year' => 2000
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

## ClientBuilder

The `\OpenSearch\ClientBuilder` class is used to create a `\OpenSearch\Client` instance. It provides a fluent interface for configuring the client.

### `setHosts`

This method allows you to set the hosts to use for the client. By default, the `RoundRobinSelector` selector is active, which will select a host from the list of hosts in a round-robin fashion.

```php
<?php
    $client = (new \OpenSearch\ClientBuilder())
        ->setHosts(['https://localhost:9200'])
        ->build();
```

### `setSelector`

This method allows you to set the host selection mode to use for the client.

```php
<?php
    $client = (new \OpenSearch\ClientBuilder())
        // Class needs to implement \OpenSearch\ConnectionPool\Selectors\SelectorInterface
        ->setSelector(new \OpenSearch\ConnectionPool\Selectors\RandomSelector())
        ->build();
```

### `setBasicAuthentication`

This method allows you to set the basic authentication credentials to use for the client.

```php
$client = (new \OpenSearch\ClientBuilder())
    ->setBasicAuthentication('username', 'password')
    ->build();
```

### `setSigV4CredentialProvider` for AWS OpenSearch Service

This method allows you to enable AWS SigV4 authentication for the client. The AWS SDK is required for this to work.

```php
$client = (new \OpenSearch\ClientBuilder())
    ->setSigV4Region('us-east-2')

    ->setSigV4Service('es')
    
    // Default credential provider.
    ->setSigV4CredentialProvider(true)
    
    ->setSigV4CredentialProvider([
      'key' => 'awskeyid',
      'secret' => 'awssecretkey',
    ])
    
    ->build();
```

### `setSigV4CredentialProvider` for AWS OpenSearch Serverlss Service

This method allows you to enable AWS SigV4 authentication for the client. The AWS SDK is required for this to work.

```php
$client = (new \OpenSearch\ClientBuilder())
    ->setSigV4Region('us-east-2')

    ->setSigV4Service('aoss')
    
    // Default credential provider.
    ->setSigV4CredentialProvider(true)
    
    ->setSigV4CredentialProvider([
      'key' => 'awskeyid',
      'secret' => 'awssecretkey',
    ])
    
    ->build();
```


### `setConnectionParams`

This method allows you to set custom curl options such as timeout/compression/etc.

```php
$client = (new \OpenSearch\ClientBuilder())
    ->setConnectionParams([
        'client' => [
            'curl' => [
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_ENCODING => 'gzip',
            ]
        ]
    ])
    ->build();
```

### `setLogger`

This method allows you to set a PSR-3 logger to use for the client. This will log all failing requests and responses. If you want to have more verbose logging, you can set also a tracer logger with `setTracer` method.

```php
<?php
    $client = (new \OpenSearch\ClientBuilder())
        ->setLogger($monologLogger)
        ->build();
```

### `setRetries`

This method allows you to set the number of retries to use for the client.

```php

$client = (new \OpenSearch\ClientBuilder())
    ->setRetries(3)
    ->build();
```
## Disabling Port Modification

To prevent port modifications, pass an `includePortInHostHeader` option into `ClientBuilder::fromConfig`.
This will ensure that the port from the supplied URL is unchanged. 

The following example will force port `9100` usage.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$config = [
    'Hosts' => ['https://localhost:9100'],
    'BasicAuthentication' => [username: 'admin', password: 'admin'],
    'SSLVerification' => false,
    'includePortInHostHeader' => true, // forces port from Hosts URL
];

$client = \OpenSearch\ClientBuilder::fromConfig($config);

...
```