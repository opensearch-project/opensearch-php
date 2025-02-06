- [Using PSR Interfaces](#using-psr-interfaces)
  - [Configuring Guzzle HTTP Client](#configuring-guzzle-http-client)
  - [Configuring Symfony HTTP Client](#configuring-symfony-http-client)

# Using PSR Interfaces

The opensearch-php client uses the following PSR interfaces: 

- [PSR-7 HTTP message interfaces](https://www.php-fig.org/psr/psr-7/)
- [PSR-17 HTTP Factories](https://www.php-fig.org/psr/psr-17/)
- [PSR-18 HTTP Client](https://www.php-fig.org/psr/psr-18/)

While it's recommended to use `OpenSearch\GuzzleClientFactory` and `OpenSearch\SymfonyClientFactory`, you can have more control over the construction of the client as described below.

## Configuring Guzzle HTTP Client

To configure [Guzzle](https://docs.guzzlephp.org/en/stable/) as a PSR HTTP Client use the following example:

```php
$guzzleClient = new \GuzzleHttp\Client([
    'base_uri' => 'https://localhost:9200',
    'auth' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify' => false,
    'retries' => 2,
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'User-Agent' => sprintf('opensearch-php/%s (%s; PHP %s)', \OpenSearch\Client::VERSION, PHP_OS, PHP_VERSION),
    ]
]);

$guzzleHttpFactory = new \GuzzleHttp\Psr7\HttpFactory();

$serializer = new \OpenSearch\Serializers\SmartSerializer();

$requestFactory = new \OpenSearch\RequestFactory(
    $guzzleHttpFactory,
    $guzzleHttpFactory,
    $guzzleHttpFactory,
    $serializer,
);

$transport = (new OpenSearch\TransportFactory())
    ->setHttpClient($guzzleClient)
    ->setRequestFactory($requestFactory)
    ->create();

$endpointFactory = new \OpenSearch\EndpointFactory();
$client = new \OpenSearch\Client($transport, $endpointFactory, []);

// Send a request to the 'info' endpoint.
$info = $client->info();
```

## Configuring Symfony HTTP Client

To configure [Symfony HTTP Client](https://symfony.com/doc/current/http_client.html) as a PSR HTTP Client using the following example:

```php
$symfonyPsr18Client = (new \Symfony\Component\HttpClient\Psr18Client())->withOptions([
    'base_uri' => 'https://localhost:9200',
    'auth_basic' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify_peer' => false,
    'max_retries' => 2,
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
]);

$serializer = new \OpenSearch\Serializers\SmartSerializer();

$requestFactory = new \OpenSearch\RequestFactory(
    $symfonyPsr18Client,
    $symfonyPsr18Client,
    $symfonyPsr18Client,
    $serializer,
);

$transport = (new \OpenSearch\TransportFactory())
    ->setHttpClient($symfonyPsr18Client)
    ->setRequestFactory($requestFactory)
    ->create();

$endpointFactory = new \OpenSearch\EndpointFactory();
$client = new \OpenSearch\Client($transport, $endpointFactory, []);

// Send a request to the 'info' endpoint.
$info = $client->info();

```
