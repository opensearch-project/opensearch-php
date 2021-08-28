<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */

use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\Tests\Utility;

// Set the default timezone. While this doesn't cause any tests to fail, PHP
// complains if it is not set in 'date.timezone' of php.ini.
date_default_timezone_set('UTC');

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die("Dependencies must be installed using composer:\n\nphp composer.phar install --dev\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}

require dirname(__DIR__) . '/vendor/autoload.php';

printf("********************************************************\n");
printf("** Download the YAML test from Elasticsearch artifacts\n");
printf("********************************************************\n");
printf("Executing %s...\n", basename(__FILE__));

$client = Utility::getClient();

printf("Getting the Elasticsearch build_hash:\n");
try {
    $serverInfo = $client->info();
    print_r($serverInfo);
} catch (NoNodesAvailableException $e) {
    printf("ERROR: Host %s is offline\n", Utility::getHost());
    exit(1);
}

$version = $serverInfo['version']['number'];
$artifactFile = sprintf("rest-resources-zip-%s.zip", $version);
$tempFilePath = sprintf("%s/%s.zip", sys_get_temp_dir(), $serverInfo['version']['build_hash']);

if (!file_exists($tempFilePath)) {
    // Download of Elasticsearch rest-api artifacts
    $json = file_get_contents("https://artifacts-api.elastic.co/v1/versions/$version");
    if (empty($json)) {
        printf("ERROR: I cannot download the artifcats from https://artifacts-api.elastic.co/v1/versions/%s\n", $version);
        exit(1);
    }
    $content = json_decode($json, true);
    foreach ($content['version']['builds'] as $builds) {
        if ($builds['projects']['elasticsearch']['commit_hash'] === $serverInfo['version']['build_hash']) {
            // Download the artifact ZIP file (rest-resources-zip-$version.zip)
            printf("Download %s\n", $builds['projects']['elasticsearch']['packages'][$artifactFile]['url']);
            if (!copy($builds['projects']['elasticsearch']['packages'][$artifactFile]['url'], $tempFilePath)) {
                printf("ERROR: failed to download %s\n", $artifactFile);
            }
            break;
        }
    }
} else {
    printf("The file %s already exists\n", $tempFilePath);
}

if (!file_exists($tempFilePath)) {
    printf("ERROR: the commit_hash %s has not been found\n", $serverInfo['version']['build_hash']);
    exit(1);
}
$zip = new ZipArchive();
$zip->open($tempFilePath);
printf("Extracting %s\ninto %s/rest-spec/%s\n", $tempFilePath, __DIR__, $serverInfo['version']['build_hash']);
$zip->extractTo(sprintf("%s/rest-spec/%s", __DIR__, $serverInfo['version']['build_hash']));
$zip->close();

printf("Rest-spec API installed successfully!\n\n");
