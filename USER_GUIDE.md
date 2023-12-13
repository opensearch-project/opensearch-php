- [User Guide](#user-guide)
  - [Example usage](#example-usage)
# User Guide

Install this client using Composer into your project `composer req opensearch-project/opensearch-php`

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
        $this->client = OpenSearch\ClientBuilder::fromConfig([
            'hosts' => [
                'https://localhost:9200'
            ],
            'retries' => 2,
            'handler' => OpenSearch\ClientBuilder::multiHandler()
        ]);

        // OR via Builder
        // $this->client = (new \OpenSearch\ClientBuilder())
        //     ->setHosts(['https://localhost:9200'])
        //     ->setBasicAuthentication('admin', 'admin') // For testing only. Don't store credentials in code.
        //     // or, if using AWS SigV4 authentication:
        //     ->setSigV4Region('us-east-2')
        //     ->setSigV4CredentialProvider(true)
        //     ->setSSLVerification(false) // For testing only. Use certificate for validation
        //     ->build();
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
          'query' => "SELECT * FROM INDEX_NAME WHERE name = 'wrecking'",
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

* [ML Commons](guides/ml-commons.md)
