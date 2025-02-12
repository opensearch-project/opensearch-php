- [User Guide](#user-guide)
  - [Example usage](#example-usage)
  - [Client Factories](#client-factories)
    - [Guzzle Client Factory](#guzzle-client-factory)
    - [Symfony Client Factory](#symfony-client-factory)
  - [ClientBuilder (Deprecated)](#clientbuilder-deprecated)
  - [Advanced Features](#advanced-features)

# User Guide

Install this client using Composer into your project 
```bash
composer require opensearch-project/opensearch-php
```

Install a PSR-18 compatible HTTP client. For example, to use Guzzle:
```bash
composer require guzzlehttp/guzzle
```

Alternatively, you can use Symfony:
```bash
composer require symfony/http-client
```

## Example usage

```php

<?php
require __DIR__ . '/vendor/autoload.php';

define('INDEX_NAME', 'test_elastic_index_name2');

class MyOpenSearchClass
{

    protected ?\OpenSearch\Client $client;
    protected $existingID = 1668504743;
    protected $deleteID = 1668504743;
    protected $bulkIds = [];


    public function __construct()
    {
        // Simple Setup
        $this->client = (new \OpenSearch\GuzzleClientFactory())->create([
            'base_uri' => 'https://localhost:9200',
            'auth' => ['admin', getenv('OPENSEARCH_PASSWORD')],
            'verify' => false, // Disables SSL verification for local development.
        ]);
    }


    // Create an index with non-default settings.
    public function createIndex()
    {
        $this->client->indices()->create([
            'index' => INDEX_NAME,
            'body' => [
                'settings' => [
                    'index' => [
                        'number_of_shards' => 4
                    ]
                ]
            ]
        ]);
    }

    public function info()
    {
        // Print OpenSearch version information on console.
        var_dump($this->client->info());
    }

    // Create a document
    public function create()
    {
        $time = time();
        $this->existingID = $time;
        $this->deleteID = $time . '_uniq';


        // Create a document passing the id
        $this->client->create([
            'id' => $time,
            'index' => INDEX_NAME,
            'body' => $this->getData($time)
        ]);

        // Create a document passing the id
        $this->client->create([
            'id' => $this->deleteID,
            'index' => INDEX_NAME,
            'body' => $this->getData($time)
        ]);

        // Create a document without passing the id (will be generated automatically)
        $this->client->create([
            'index' => INDEX_NAME,
            'body' => $this->getData($time + 1)
        ]);

        //This should throw an exception because ID already exists
        // $this->client->create([
        //     'id' => $this->existingID,
        //     'index' => INDEX_NAME,
        //     'body' => $this->getData($this->existingID)
        // ]);
    }

    public function update()
    {
        $this->client->update([
            'id' => $this->existingID,
            'index' => INDEX_NAME,
            'body' => [
                //data must be wrapped in 'doc' object
                'doc' => ['name' => 'updated']
            ]
        ]);
    }

    public function bulk()
    {
        $bulkData = [];
        $time = time();
        for ($i = 0; $i < 20; $i++) {
            $id = ($time + $i) . rand(10, 200);
            $bulkData[] = [
                'index' => [
                    '_index' => INDEX_NAME,
                    '_id' => $id,
                ]
            ];
            $this->bulkIds[] = $id;
            $bulkData[] = $this->getData($time + $i);
        }
        //will not throw exception! check $response for error
        $response = $this->client->bulk([
            //default index
            'index' => INDEX_NAME,
            'body' => $bulkData
        ]);

        //give elastic a little time to create before update
        sleep(2);

        // bulk update
        for ($i = 0; $i < 15; $i++) {
            $bulkData[] = [
                'update' => [
                    '_index' => INDEX_NAME,
                    '_id' => $this->bulkIds[$i],
                ]
            ];
            $bulkData[] = [
                'doc' => [
                    'name' => 'bulk updated'
                ]
            ];
        }

        //will not throw exception! check $response for error
        $response = $this->client->bulk([
            //default index
            'index' => INDEX_NAME,
            'body' => $bulkData
        ]);
    }
    public function deleteByQuery(string $query)
    {
        if ($query == '') {
            return;
        }
        $this->client->deleteByQuery([
            'index' => INDEX_NAME,
            'q' => $query
        ]);
    }

    // Delete a single document
    public function deleteByID()
    {
        $this->client->delete([
            'id' => $this->deleteID,
            'index' => INDEX_NAME,
        ]);
    }

    public function search()
    {
        $docs = $this->client->search([
            //index to search in or '_all' for all indices
            'index' => INDEX_NAME,
            'size' => 1000,
            'body' => [
                'query' => [
                    'prefix' => [
                        'name' => 'wrecking'
                    ]
                ]
            ]
        ]);
        var_dump($docs['hits']['total']['value'] > 0);

        // Search for it
        $docs = $this->client->search([
            'index' => INDEX_NAME,
            'body' => [
                'size' => 5,
                'query' => [
                    'multi_match' => [
                        'query' => 'miller',
                        'fields' => ['title^2', 'director']
                    ]
                ]
            ]
        ]);
        var_dump($docs['hits']['total']['value'] > 0);
    }

    // Write queries in SQL
    public function searchUsingSQL()
    {
        $docs = $this->client->sql()->query([
          'query' => "SELECT * FROM " . INDEX_NAME . " WHERE name = 'wrecking'",
          'format' => 'json'
        ]);
        var_dump($docs['hits']['total']['value'] > 0);
    }

    public function getMultipleDocsByIDs()
    {
        $docs = $this->client->search([
            //index to search in or '_all' for all indices
            'index' => INDEX_NAME,
            'body' => [
                'query' => [
                    'ids' => [
                        'values' => $this->bulkIds
                    ]
                ]
            ]
        ]);
        var_dump($docs['hits']['total']['value'] > 0);
    }

    public function getOneByID()
    {
        $docs = $this->client->search([
            //index to search in or '_all' for all indices
            'index' => INDEX_NAME,
            'size' => 1,
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [
                                '_id' => $this->existingID
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        var_dump($docs['hits']['total']['value'] > 0);
    }

    public function searchByPointInTime()
    {
        $result = $this->client->createPointInTime([
            'index' => INDEX_NAME,
            'keep_alive' => '10m'
        ]);
        $pitId = $result['pit_id'];

        // Get first page of results in Point-in-Time
        $result = $this->client->search([
            'body' => [
                'pit' => [
                    'id' => $pitId,
                    'keep_alive' => '10m',
                ],
                'size' => 10, // normally you would do 10000
                'query' => [
                    'match_all' => (object)[]
                ],
                'sort' => '_id',
            ]
        ]);
        var_dump($result['hits']['total']['value'] > 0);

        $last = end($result['hits']['hits']);
        $lastSort = $last['sort'] ?? null;

        // Get next page of results in Point-in-Time
        $result = $this->client->search([
            'body' => [
                'pit' => [
                    'id' => $pitId,
                    'keep_alive' => '10m',
                ],
                'search_after' => $lastSort,
                'size' => 10, // normally you would do 10000
                'query' => [
                    'match_all' => (object)[]
                ],
                'sort' => '_id',
            ]
        ]);
        var_dump($result['hits']['total']['value'] > 0);

        // Close Point-in-Time
        $result = $this->client->deletePointInTime([
            'body' => [
              'pit_id' => $pitId,
            ]
        ]);
        var_dump($result['pits'][0]['successful']);
    }

    // Delete index
    public function deleteByIndex()
    {
        $this->client->indices()->delete([
            'index' => INDEX_NAME
        ]);
    }

    //simple data to index
    public function getData($time = -1)
    {
        if ($time == -1) {
            $time = time();
        }
        return [
            'name' => date('c', $time) . " - i came in like a wrecking ball",
            'time' => $time,
            'date' => date('c', $time)
        ];
    }
}

try {

    $e = new MyOpenSearchClass();
    $e->info();
    $e->createIndex();
    $e->create();
    //give elastic a little time to create before update
    sleep(2);
    $e->update();
    $e->bulk();
    $e->getOneByID();
    $e->getMultipleDocsByIDs();
    $e->search();
    $e->searchUsingSQL();
    $e->searchByPointInTime();
    $e->deleteByQuery('');
    $e->deleteByID();
    $e->deleteByIndex();
} catch (\Throwable $th) {
    echo 'uncaught error ' . $th->getMessage() . "\n";
}

```

## Client Factories

You can create an OpenSearch Client using any [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible HTTP client. Two factories are provided to reduce the boilerplate code required to create a client using [Guzzle](https://docs.guzzlephp.org/en/stable/) or [Symfony](https://symfony.com/doc/current/http_client.html) HTTP clients.

The `GuzzleClientFactory` and `SymfonyClientFactory` classes are used to create an OpenSearch Client instance.

### Guzzle Client Factory

This factory creates an OpenSearch Client instance using the Guzzle HTTP client.

```bash
composer require guzzlehttp/guzzle
```

```php
$client = (new \OpenSearch\GuzzleClientFactory())->create([
    'base_uri' => 'https://localhost:9200',
    'auth' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify' => false,
]);
```
`GuzzleClientFactory::__construct()` accepts an `int $maxRetries` as the first argument to enable retrying a request
when a `500 Server Error` status code is received in the response, or network connection error occurs. A PSR-3 Logger
can be passed as the second argument to log the retries.

[Guzzle Request options](https://docs.guzzlephp.org/en/stable/request-options.html) can be passed to the 
`create()` method. The `base_uri` option is *required*.

### Symfony Client Factory

This factory creates an OpenSearch Client instance using the Symfony HTTP client.

```bash
composer require symfony/http-client
```

```php
$client = (new \OpenSearch\SymfonyClientFactory())->create([
    'base_uri' => 'https://localhost:9200',
    'auth_basic' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify_peer' => false,
]);
````

The `SymfonyClientFactory` constructor accepts an `int $maxRetries` as the first argument to enable retrying a request
when a `500 Server Error` status code is received in the response, or network connection error occurs. A PSR-3 Logger
can be passed as the second argument to log the retries.

[Symfony HTTP Client configuration options](https://symfony.com/doc/current/http_client.html#configuration-options) can 
be passed to the `create()` method. The `base_uri` option is *required*.

## ClientBuilder (Deprecated)

**`ClientBuilder` is deprecated in 2.4.0 and will be removed in 3.0.0. Use the `GuzzleClientFactory` or `SymfonyClientFactory` instead.**


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

### `setSigV4CredentialProvider` for AWS OpenSearch Serverless Service

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

To prevent port modifications, include the `includePortInHostHeader` option into `ClientBuilder::fromConfig`.
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

## Advanced Features

* [Authentication](guides/auth.md)
* [ML Commons](guides/ml-commons.md)
* [Sending Raw JSON Requests](guides/raw-request.md)
* [Using PSR Interfaces](guides/psr.md)
