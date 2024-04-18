<?php

use OpenSearch\ClientBuilder;

require dirname(__DIR__) . '/vendor/autoload.php';

$retries = 0;
$maxRetries = 10;

while (true) {
    try {
        $client = ClientBuilder::create()->build();
        $client->ping();
        $info = $client->info();
        echo 'OpenSearch ' . $info['version']['number'] . ' is up and running' . PHP_EOL;
        exit(0);
    } catch (Throwable $e) {
        if ($retries === $maxRetries) {
            echo 'Cannot reach search server' . PHP_EOL;
            exit(1);
        }

        sleep(5);
        $retries++;
    }
}
