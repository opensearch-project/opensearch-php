<?php

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 */

use OpenSearch\Client;

require_once __DIR__ . '/vendor/autoload.php';

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
$guzzleHttpFactory = new \GuzzleHttp\Psr7\HttpFactory();
$transport = (new OpenSearch\TransportFactory())
    ->setHttpClient($guzzleClient)
    ->setPsrRequestFactory($guzzleHttpFactory)
    ->setStreamFactory($guzzleHttpFactory)
    ->setUriFactory($guzzleHttpFactory)
    ->create();

$endpointFactory = new \OpenSearch\EndpointFactory();
$client = new Client($transport, $endpointFactory, []);

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


$transport = (new OpenSearch\TransportFactory())
    ->setHttpClient($symfonyPsr18Client)
    ->setPsrRequestFactory($symfonyPsr18Client)
    ->setStreamFactory($symfonyPsr18Client)
    ->setUriFactory($symfonyPsr18Client)
    ->create();

$client = new Client($transport, $endpointFactory, []);

$info = $client->info();
