- [Upgrading OpenSearch PHP Client](#upgrading-opensearch-php-client)
    - [Upgrading to >= 2.0.0](#upgrading-to--240)
        - [HTTP Client Auto-Discovery](#http-client-auto-discovery)
        - [Configuring Guzzle HTTP Client in 2.x](#configuring-guzzle-http-client-in-2x)
        - [Configuring Symfony HTTP Client in 2.x](#configuring-symfony-http-client-in-2x)

# Upgrading OpenSearch PHP Client

## Upgrading to >= 2.4.0

openseach-php removes the hard-coded dependency on the [Guzzle HTTP client](https://docs.guzzlephp.org/en/stable/#) and switches to the following PSR interfaces: 

- [PSR-7 HTTP message interfaces](https://www.php-fig.org/psr/psr-7/)
- [PSR-17 HTTP Factories](https://www.php-fig.org/psr/psr-17/)
- [PSR-18 HTTP Client](https://www.php-fig.org/psr/psr-18/)

You can continue to use Guzzle, but will need to configure it as a PSR-18 HTTP Client.

### HTTP Client Auto-Discovery

opensearch-php 2.x will try and discover and install a PSR HTTP Client using [PHP-HTTP Discovery](https://docs.php-http.org/en/latest/discovery.html) 
if one is not explicitly provided.

```php
$transport = (new \OpenSearch\TransportFactory())->create();
$endpointFactory = new \OpenSearch\EndpointFactory();
$client = new Client($transport, $endpointFactory, []);

// Send a request to the 'info' endpoint.
$info = $client->info();
```

### Configuring Guzzle HTTP Client in 2.x

To configure Guzzle as a PSR HTTP Client with the similar configuration to opensearch 1.x you can use the following example:

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

### Configuring Symfony HTTP Client in 2.x

You can configure [Symfony HTTP Client](https://symfony.com/doc/current/http_client.html) as a PSR HTTP Client using
the following example:

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
