- [Upgrading OpenSearch PHP Client](#upgrading-opensearch-php-client)
  - [Upgrading to \>= 2.4.0](#upgrading-to--240)
    - [PSR-18 HTTP Client Interfaces](#psr-18-http-client-interfaces)
    - [Configuring Guzzle HTTP Client in 2.4.x](#configuring-guzzle-http-client-in-24x)
    - [Configuring Symfony HTTP Client in 2.4.x](#configuring-symfony-http-client-in-24x)

# Upgrading OpenSearch PHP Client

## Upgrading to >= 2.4.0

The openseach-php library removes the hard-coded dependency on the [Guzzle HTTP client](https://docs.guzzlephp.org/en/stable/#) and switches to the following PSR interfaces: 

- [PSR-7 HTTP message interfaces](https://www.php-fig.org/psr/psr-7/)
- [PSR-17 HTTP Factories](https://www.php-fig.org/psr/psr-17/)
- [PSR-18 HTTP Client](https://www.php-fig.org/psr/psr-18/)

You can continue to use Guzzle, but will need to configure it as a PSR-18 HTTP Client.

### PSR-18 HTTP Client Interfaces

Starting with opensearch-php 2.4.0 you can use any PSR-18 compatible HTTP client. 

To simplify creating a Client, we provide two factories to create PSR-18 HTTP clients for Guzzle and Symfony HTTP clients since opensearch-php 2.5.0.

### Configuring Guzzle HTTP Client in 2.4.x

To configure Guzzle as a PSR HTTP Client with the similar configuration to opensearch-php 1.x you can use the following example:

Ensure the Guzzle packages are installed via composer:

```php
composer require guzzlehttp/guzzle
```

```php
$client = (new \OpenSearch\GuzzleClientFactory())->create([
    'base_uri' => 'https://localhost:9200',
    'auth' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify' => false,
]);

// Send a request to the 'info' endpoint.
$info = $client->info();
```

### Configuring Symfony HTTP Client in 2.4.x

You can configure [Symfony HTTP Client](https://symfony.com/doc/current/http_client.html) as a PSR HTTP Client using the following example:

```php
composer require symfony/http-client
```

```php
$client = (new \OpenSearch\SymfonyClientFactory())->create([
    'base_uri' => 'https://localhost:9200',
    'auth_basic' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify_peer' => false,
]);

// Send a request to the 'info' endpoint.
$info = $client->info();
```
