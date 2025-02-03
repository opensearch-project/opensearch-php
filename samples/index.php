<?php

use GuzzleHttp\Psr7\HttpFactory;
use OpenSearch\Client;
use OpenSearch\EndpointFactory;
use OpenSearch\RequestFactory;
use OpenSearch\Serializers\SmartSerializer;
use OpenSearch\TransportFactory;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 */

require_once __DIR__ . '/vendor/autoload.php';

// Auto-configure by discovery example

$transport = (new TransportFactory())->create();
$endpointFactory = new EndpointFactory();
$client = new Client($transport, $endpointFactory, []);

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
        'User-Agent' => sprintf('opensearch-php/%s (%s; PHP %s)', Client::VERSION, PHP_OS, PHP_VERSION),
    ]
]);

$guzzleHttpFactory = new HttpFactory();

$serializer = new SmartSerializer();

$requestFactory = new RequestFactory(
    $guzzleHttpFactory,
    $guzzleHttpFactory,
    $guzzleHttpFactory,
    $serializer,
);

$transport = (new TransportFactory())
    ->setHttpClient($guzzleClient)
    ->setRequestFactory($requestFactory)
    ->create();

$endpointFactory = new EndpointFactory();
$client = new Client($transport, $endpointFactory, []);

// Send a request to the 'info' endpoint.
$info = $client->info();

// Symfony example

$symfonyPsr18Client = (new Psr18Client())->withOptions([
    'base_uri' => 'https://localhost:9200',
    'auth_basic' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify_peer' => false,
    'max_retries' => 2,
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],
]);

$serializer = new SmartSerializer();

$requestFactory = new RequestFactory(
    $symfonyPsr18Client,
    $symfonyPsr18Client,
    $symfonyPsr18Client,
    $serializer,
);

$transport = (new TransportFactory())
    ->setHttpClient($symfonyPsr18Client)
    ->setRequestFactory($requestFactory)
    ->create();

$client = new Client($transport, $endpointFactory, []);

// Send a request to the 'info' endpoint.
$info = $client->info();
