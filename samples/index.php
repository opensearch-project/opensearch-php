<?php

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 */

require_once __DIR__ . '/vendor/autoload.php';

$client = OpenSearch\ClientBuilder::fromConfig([
   'Hosts' => [
      'https://localhost:9200'
   ],
   'BasicAuthentication' => ['admin', getenv('OPENSEARCH_PASSWORD')],
   'Retries' => 2,
   'SSLVerification' => false
]);

$info = $client->info();

echo "{$info['version']['distribution']}: {$info['version']['number']}\n";
