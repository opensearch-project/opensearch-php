<?php
/**
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */


declare(strict_types = 1);

namespace Elasticsearch\Tests;

use Exception;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\ElasticsearchException;

class Utility
{
    /**
     * @var string
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
        return 'http://elastic:changeme@localhost:9200';
    }

    /**
     * Build a Client based on ENV variables
     */
    public static function getClient(): Client
    {
        $clientBuilder = ClientBuilder::create()
            ->setHosts([self::getHost()]);
        $clientBuilder->setConnectionParams([
            'client' => [
                'headers' => [
                    'Accept' => []
                ]
            ]
        ]);
        $clientBuilder->setSSLVerification(false);
        return $clientBuilder->build();
    }


    private static function getVersion(Client $client): string
    {
        if (!isset(self::$version)) {
            $result = $client->info();
            self::$version = $result['version']['number'];
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
        if (version_compare(self::getVersion($client), '7.3.99') > 0) {
            self::deleteAllSLMPolicies($client);  
        }

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
        } catch (ElasticsearchException $e) {
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
            if (version_compare(self::getVersion($client), '7.8.99') > 0) {
                $client->indices()->deleteDataStream([
                    'name' => '*',
                    'expand_wildcards' => 'all'
                ]);
            }
        } catch (ElasticsearchException $e) {
            // We hit a version of ES that doesn't understand expand_wildcards, try again without it
            try {
                $client->indices()->deleteDataStream([
                    'name' => '*'
                ]);
            } catch (ElasticsearchException $e) {
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
        $expand = 'open,closed';
        if (version_compare(self::getVersion($client), '7.6.99') > 0) {
            $expand .= ',hidden';
        }
        try {
            $client->indices()->delete([
                'index' => '*,-.ds-ilm-history-*',
                'expand_wildcards' => $expand
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
     * A set of ILM policies that should be preserved between runs.
     * 
     * @see ESRestTestCase.java:preserveILMPolicyIds
     */
    private static function preserveILMPolicyIds(): array
    {
        return [
            "ilm-history-ilm-policy", 
            "slm-history-ilm-policy",
            "watch-history-ilm-policy", 
            "ml-size-based-ilm-policy", 
            "logs", 
            "metrics"
        ];
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
