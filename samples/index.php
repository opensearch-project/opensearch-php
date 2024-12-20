<?php

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 */

require_once __DIR__ . '/vendor/autoload.php';

// Auto-configure by discovery example

$transport = (new \OpenSearch\TransportFactory())->create();
$endpointFactory = new \OpenSearch\EndpointFactory();
$client = new \OpenSearch\Client($transport, $endpointFactory, []);

// Send a request to the 'info' endpoint.
$info = $client->info();

// Guzzle example

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

// Symfony example

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

$client = new \OpenSearch\Client($transport, $endpointFactory, []);

// Send a request to the 'info' endpoint.
$info = $client->info();
