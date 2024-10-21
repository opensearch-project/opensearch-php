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
$requestFactory = new \OpenSearch\RequestFactory();
$transport = new OpenSearch\Transport($guzzleClient, $requestFactory);

$client = (new \OpenSearch\ClientBuilder($transport))->build();

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

$transport = new OpenSearch\Transport($symfonyPsr18Client, $requestFactory);

$client = (new \OpenSearch\ClientBuilder($transport))->build();

$info = $client->info();
