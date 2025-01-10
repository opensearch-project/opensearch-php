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
$endpoint = "https://localhost:9200"
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

```php
$symfonyPsr18Client = (new \Symfony\Component\HttpClient\Psr18Client())->withOptions([
    'base_uri' => $endpoint,
    'auth_basic' => [$username, $password],
    'verify_peer' => false, // for testing only
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
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

```php
$symfonyPsr18Client = (new \Symfony\Component\HttpClient\Psr18Client())->withOptions([
    'base_uri' => $endpoint,
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
]);

$serializer = new \OpenSearch\Serializers\SmartSerializer();
$endpointFactory = new \OpenSearch\EndpointFactory();

$signer = new Aws\Signature\SignatureV4(
    $service,
    $region
);

$credentials = new Aws\Credentials\Credentials(
    $aws_access_key_id,
    $aws_secret_access_key,
    $aws_session_token
);

$signingClient = new \OpenSearch\Aws\SigningClientDecorator(
    $symfonyPsr18Client,
    $credentials,
    $signer, 
    [
        'Host' => parse_url(getenv("ENDPOINT"))['host']
    ]
);

$requestFactory = new \OpenSearch\RequestFactory(
    $symfonyPsr18Client,
    $symfonyPsr18Client,
    $symfonyPsr18Client,
    $serializer,
);

$transport = (new \OpenSearch\TransportFactory())
    ->setHttpClient($signingClient)
    ->setRequestFactory($requestFactory)
    ->create();

$client = new \OpenSearch\Client(
    $transport,
    $endpointFactory,
    []
);
```