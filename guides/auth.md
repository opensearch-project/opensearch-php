- [Authentication](#authentication)
  - [Basic Auth](#basic-auth)
    - [Using `\OpenSearch\ClientBuilder`](#using-opensearchclientbuilder)
    - [Using a Psr Client](#using-a-psr-client)
  - [IAM Authentication](#iam-authentication)
    - [Using `\OpenSearch\ClientBuilder`](#using-opensearchclientbuilder-1)
    - [Using a Psr Client](#using-a-psr-client-1)

# Authentication

OpenSearch allows you to use different methods for the authentication.

## Basic Auth

```php
$endpoint = "http://localhost:9200"
$username = "admin"
$password = "..."
```

### Using `\OpenSearch\ClientBuilder`

```php
$client = (new \OpenSearch\ClientBuilder())
    ->setBasicAuthentication($username, $password) 
    ->setHosts([$endpoint])
    ->setSSLVerification(false) // for testing only
    ->build();
```

### Using a Psr Client

Using Symfony HTTP Client:

```php
$client = (new SymfonyClientFactory())->create([
    'base_uri' => $endpoint,
    'auth_basic' => [$username, $password],
]);
```

Using Guzzle:

```php
$client = (new GuzzleClientFactory())->create([
    'base_uri' => $endpoint,
    'auth' => [$username, $password],
]);
```

## IAM Authentication

This library supports IAM-based authentication when communicating with OpenSearch clusters running in Amazon Managed OpenSearch and OpenSearch Serverless.

```php
$endpoint = "https://search-....us-west-2.es.amazonaws.com"
$region = "us-west-2"
$service = "es"
$aws_access_key_id = ...
$aws_secret_access_key = ...
$aws_session_token = ...
```

### Using `\OpenSearch\ClientBuilder`

```php
$client = (new \OpenSearch\ClientBuilder())
  ->setHosts([$endpoint])
  ->setSigV4Region($region)    
  ->setSigV4Service('es')
  ->setSigV4CredentialProvider([
      'key' => $aws_access_key_id,
      'secret' => $aws_secret_access_key,
      'token' => $aws_session_token
    ])
  ->build();
```

### Using a Psr Client

We can use the `AwsSigningHttpClientFactory` to create an HTTP Client to sign the requests using the AWS SDK for PHP.

Require a PSR-18 client (e.g. Symfony) and the AWS SDK for PHP:

```bash
composer require symfony/http-client aws/aws-sdk-php
```

Create an OpenSearch Client using the Symfony HTTP Client and the AWS SDK for PHP:

```php
$client = (new SymfonyClientFactory())->create([
    'base_uri' => $endpoint,
    'auth_aws' => [
        'region' => $region,
        'service' => 'es',
        'credentials' => [
            'access_key' => $aws_access_key_id,
            'secret_key' => $aws_secret_access_key,
            'session_token' => $aws_session_token,
        ],
    ],
]);
```
