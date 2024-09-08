<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * OpenSearch PHP client
 *
 * @link      https://github.com/opensearch-project/opensearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Tests;

use Exception;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use OpenSearch\Common\Exceptions\OpenSearchException;

class Utility
{
    /**
     * @var array{number: string, distribution?: string, build_flavor: string, build_date: string, build_hash: string, build_snapshot: bool, build_type: string, lucene_version: string, minimum_index_compatibility_version: string, minimum_wire_compatibility_version: string}|null
     */
    private static $version;

    /**
     * Get the host URL based on ENV variables
     */
    public static function getHost(): ?string
    {
        $url = getenv('OPENSEARCH_URL');
        if (false !== $url) {
            return $url;
        }

        $password = getenv('OPENSEARCH_INITIAL_ADMIN_PASSWORD') ?: 'admin';

        return 'https://admin:' . $password . '@localhost:9200';
    }

    /**
     * Build a Client based on ENV variables
     */
    public static function getClient(): Client
    {
        $clientBuilder = ClientBuilder::create()
            ->setHosts([self::getHost()]);


        $clientBuilder->setSSLVerification(false);
        return $clientBuilder->build();
    }

    /**
     * Check if cluster is OpenSearch and version is greater than or equal to specified version.
     */
    public static function isOpenSearchVersionAtLeast(Client $client, string $version): bool
    {
        $versionInfo = self::getVersion($client);
        $distribution = $versionInfo['distribution'] ?? null;
        if ($distribution !== 'opensearch') {
            return false;
        }
        $versionNumber = $versionInfo['number'];
        return version_compare($versionNumber, $version, '>=');
    }

    /**
     * @return array{number: string, distribution?: string, build_flavor: string, build_date: string, build_hash: string, build_snapshot: bool, build_type: string, lucene_version: string, minimum_index_compatibility_version: string, minimum_wire_compatibility_version: string}
     */
    private static function getVersion(Client $client): array
    {
        if (!isset(self::$version)) {
            $result = $client->info();
            self::$version = $result['version'];
        }
        return self::$version;
    }

    /**
     * Clean up the cluster after a test
     *
     * @see ESRestTestCase.java:cleanUpCluster()
     */
    public static function cleanUpCluster(Client $client): void
    {
        self::wipeCluster($client);
        self::waitForClusterStateUpdatesToFinish($client);
    }

    /**
    * Delete the cluster
    *
    * @see ESRestTestCase.java:wipeCluster()
    */
    private static function wipeCluster(Client $client): void
    {
        self::deleteAllSLMPolicies($client);
        self::wipeSnapshots($client);
        self::wipeDataStreams($client);
        self::wipeAllIndices($client);

        // Delete templates
        $client->indices()->deleteTemplate([
            'name' => '*'
        ]);
        try {
            // Delete index template
            $client->indices()->deleteIndexTemplate([
                'name' => '*'
            ]);
            // Delete component template
            $client->cluster()->deleteComponentTemplate([
                'name' => '*'
            ]);
        } catch (OpenSearchException $e) {
            // We hit a version of ES that doesn't support index templates v2 yet, so it's safe to ignore
        }

        self::wipeClusterSettings($client);
    }

    /**
     * Delete all the Snapshots
     *
     * @see ESRestTestCase.java:wipeSnapshots()
     */
    private static function wipeSnapshots(Client $client): void
    {
        $repos = $client->snapshot()->getRepository([
            'repository' => '_all'
        ]);
        foreach ($repos as $repository => $value) {
            if ($value['type'] === 'fs') {
                $response = $client->snapshot()->get([
                    'repository' => $repository,
                    'snapshot' => '_all',
                    'ignore_unavailable' => true
                ]);
                if (isset($response['responses'])) {
                    $response = $response['responses'][0];
                }
                if (isset($response['snapshots'])) {
                    foreach ($response['snapshots'] as $snapshot) {
                        $client->snapshot()->delete([
                            'repository' => $repository,
                            'snapshot' => $snapshot['snapshot'],
                            'client' => [
                                'ignore' => 404
                            ]
                        ]);
                    }
                }
            }
            $client->snapshot()->deleteRepository([
                'repository' => $repository,
                'client' => [
                    'ignore' => 404
                ]
            ]);
        }
    }

    /**
     * Delete all SLM policies
     *
     * @see ESRestTestCase.java:deleteAllSLMPolicies()
     */
    private static function deleteAllSLMPolicies(Client $client): void
    {
        $policies = $client->slm()->getLifecycle();
        foreach ($policies as $policy) {
            $client->slm()->deleteLifecycle([
                'policy_id' => $policy['name']
            ]);
        }
    }

    /**
     * Delete all data streams
     *
     * @see ESRestTestCase.java:wipeDataStreams()
     */
    private static function wipeDataStreams(Client $client): void
    {
        try {
            $client->indices()->deleteDataStream([
                'name' => '*',
                'expand_wildcards' => 'all'
            ]);
        } catch (OpenSearchException $e) {
            // We hit a version of ES that doesn't understand expand_wildcards, try again without it
            try {
                $client->indices()->deleteDataStream([
                    'name' => '*'
                ]);
            } catch (OpenSearchException $e) {
                // We hit a version of ES that doesn't serialize DeleteDataStreamAction.Request#wildcardExpressionsOriginallySpecified
                // field or that doesn't support data streams so it's safe to ignore
            }
        }
    }

    /**
     * Delete all indices
     *
     * @see ESRestTestCase.java:wipeAllIndices()
     */
    private static function wipeAllIndices(Client $client): void
    {
        try {
            $client->indices()->delete([
                'index' => '*,-.ds-ilm-history-*',
                'expand_wildcards' => 'open,closed,hidden'
            ]);
        } catch (Exception $e) {
            if ($e->getCode() != '404') {
                throw $e;
            }
        }
    }

    /**
     * Reset the cluster settings
     *
     * @see ESRestTestCase.java:wipeClusterSettings()
     */
    private static function wipeClusterSettings(Client $client): void
    {
        $settings = $client->cluster()->getSettings();
        $newSettings = [];
        foreach ($settings as $name => $value) {
            if (!empty($value) && is_array($value)) {
                if (empty($newSettings[$name])) {
                    $newSettings[$name] = [];
                }
                foreach ($value as $key => $data) {
                    $newSettings[$name][$key . '.*'] = null;
                }
            }
        }
        if (!empty($newSettings)) {
            $client->cluster()->putSettings([
                'body' => $newSettings
            ]);
        }
    }

    /**
     * Wait for Cluster state updates to finish
     *
     * @see ESRestTestCase.java:waitForClusterStateUpdatesToFinish()
     */
    private static function waitForClusterStateUpdatesToFinish(Client $client, int $timeout = 30): void
    {
        $start = time();
        do {
            $result = $client->cluster()->pendingTasks();
            $stillWaiting = ! empty($result['tasks']);
        } while ($stillWaiting && time() < ($start + $timeout));
    }
}
