![OpenSearch logo](OpenSearch.svg)

- [Welcome!](#welcome)
- [Project Resources](#project-resources)
- [Code of Conduct](#code-of-conduct)
- [Compatibility with OpenSearch](#compatibility-with-opensearch)
- [License](#license)
- [Copyright](#copyright)

## Welcome!

**opensearch-php** is [a community-driven, open source fork](https://aws.amazon.com/blogs/opensource/introducing-opensearch/) of elasticsearch-php licensed under the [Apache v2.0 License](https://github.com/opensearch-project/opensearch-php/blob/main/LICENSE). For more information, see [opensearch.org](https://opensearch.org/).

## Project Resources

* [Project Website](https://opensearch.org/)
* [User Guide](https://github.com/opensearch-project/opensearch-php/blob/main/USER_GUIDE.md)
* [Developer Guide](https://github.com/opensearch-project/opensearch-php/blob/main/DEVELOPER_GUIDE.md)
* [Downloads](https://opensearch.org/downloads.html).
* [Documentation](https://opensearch.org/docs/latest/)
* Need help? Try [Forums](https://discuss.opendistrocommunity.dev/)
* [Project Principles](https://opensearch.org/#principles)
* [Contributing to OpenSearch](https://github.com/opensearch-project/opensearch-php/blob/main/CONTRIBUTING.md)
* [Maintainer Responsibilities](https://github.com/opensearch-project/opensearch-php/blob/main/MAINTAINERS.md)
* [Release Management](https://github.com/opensearch-project/opensearch-php/blob/main/RELEASING.md)
* [Admin Responsibilities](https://github.com/opensearch-project/opensearch-php/blob/main/ADMINS.md)
* [Security](https://github.com/opensearch-project/opensearch-php/blob/main/SECURITY.md)

## Code of Conduct

This project has adopted the [Amazon Open Source Code of Conduct](https://github.com/opensearch-project/opensearch-php/blob/main/CODE_OF_CONDUCT.md). For more information see the [Code of Conduct FAQ](https://aws.github.io/code-of-conduct-faq), or contact [opensource-codeofconduct@amazon.com](mailto:opensource-codeofconduct@amazon.com) with any additional questions or comments.

## Sample code

```php
<?php
include_once __DIR__ . '/holders/vendor/autoload.php';

define('INDEX_NAME', 'test_elastic_index_name2');

class EEOPen
{

    protected ?\OpenSearch\Client $client;
    protected $existingID = 1668504743;
    protected $deleteID = 1668504743;
    protected $bulkIds = [];


    public function __construct()
    {
        $url = [
            'host' => 'http://127.0.0.1',
            'port' => '9200',
        ];
        $params = [
            'hosts' => [
                $url['host'] . ':' . $url['port']
            ],
            'retries' => 2,
            'handler' =>  OpenSearch\ClientBuilder::multiHandler()
        ];
        $this->client = OpenSearch\ClientBuilder::fromConfig($params);
    }

    public function create()
    {
        $time = time();
        $this->existingID = $time;
        $this->deleteID = $time . '_uniq';
        $this->client->create([
            'id' => $time,
            'index' => INDEX_NAME,
            'body' => $this->getData($time)
        ]);
        $this->client->create([
            'id' => $this->deleteID,
            'index' => INDEX_NAME,
            'body' => $this->getData($time)
        ]);

        //This shpould throw an exception
        // $this->client->create([
        //     'id' => $this->existingID,
        //     'index' => INDEX_NAME,
        //     'body' => $this->getData($this->existingID)
        // ]);
    }

    public function  update()
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
            $bulkData[] =  [
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
            $bulkData[] =  [
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

    public function deleteByID()
    {
        $this->client->delete([
            'id' => $this->deleteID,
            'index' => INDEX_NAME,
        ]);
    }
    public function search()
    {
        $docs =    $this->client->search([
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
    }

    public function getMultipleDocsByIDs()
    {
        $docs =    $this->client->search([
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
        $docs =   $this->client->search([
            //index to search in or '_all' for all indices
            'index' => INDEX_NAME,
            'size' => 1,
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' =>    [
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

    //simple data to index
    public function  getData($time = -1)
    {
        if ($time == -1) {
            $time = time();
        }
        return  [
            'name' => date('c', $time) . "  - i came in like a wrecking ball",
            'time' => $time,
            'date' => date('c', $time)
        ];
    }
}

try {

    $e = new EEOPen();
    $e->create();
    //give elastic a little time to create before update
    sleep(2);
    $e->update();
    $e->bulk();
    $e->getOneByID();
    $e->getMultipleDocsByIDs();
    $e->search();
    $e->deleteByQuery('');
    $e->deleteByID();
} catch (\Throwable $th) {
    echo 'uncaught error ' . $th->getMessage() . "\n";
}


```


## Compatibility with OpenSearch

See [Compatibility](COMPATIBILITY.md).

## License

This project is licensed under the [Apache v2.0 License](https://github.com/opensearch-project/opensearch-php/blob/main/LICENSE).

## Copyright

Copyright OpenSearch Contributors. See [NOTICE](https://github.com/opensearch-project/opensearch-php/blob/main/NOTICE) for details.
