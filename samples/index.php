<?php

use OpenSearch\GuzzleClientFactory;
use OpenSearch\SymfonyClientFactory;

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 */

require_once __DIR__ . '/vendor/autoload.php';

// Guzzle example

$client = (new GuzzleClientFactory())->create([
    'base_uri' => 'https://localhost:9200',
    'auth' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify' => false,
]);

// Send a request to the 'info' endpoint.
$info = $client->info();

// Symfony example

$client = (new SymfonyClientFactory())->create([
    'base_uri' => 'https://localhost:9200',
    'auth_basic' => ['admin', getenv('OPENSEARCH_PASSWORD')],
    'verify_peer' => false,
]);

// Send a request to the 'info' endpoint.
$info = $client->info();
