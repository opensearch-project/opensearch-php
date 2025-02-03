<?php

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

require 'vendor/autoload.php';

use GuzzleHttp\Client;

function main()
{
    // Update CHANGELOG.md when API generator produces new code differing from existing.

    try {
        $gitStatus = shell_exec("git status");
        if ($gitStatus === null) {
            throw new \RuntimeException('Failed to execute git command.');
        }

        if (strpos($gitStatus, "Changes to be committed:") !== false ||
            strpos($gitStatus, "Changes not staged for commit:") !== false ||
            strpos($gitStatus, "Untracked files:") !== false) {
            echo "Changes detected; updating changelog.\n";

            $client = new Client();
            $response = $client->get('https://api.github.com/repos/opensearch-project/opensearch-api-specification/commits', [
                'query' => ['per_page' => 1],
                'headers' => ['User-Agent' => 'PHP']
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException(
                    'Failed to fetch opensearch-api-specification commit information. Status code: ' . $response->getStatusCode()
                );
            }

            $commitInfo = json_decode($response->getBody(), true)[0];
            $commitUrl = $commitInfo["html_url"];
            $latestCommitSha = $commitInfo["sha"];

            $changelogPath = "CHANGELOG.md";
            $content = file_get_contents($changelogPath);
            if ($content === false) {
                throw new \RuntimeException('Failed to read CHANGELOG.md');
            }

            if (strpos($content, $commitUrl) === false) {
                if (strpos($content, "### Updated APIs") !== false) {
                    $fileContent = str_replace(
                        "### Updated APIs",
                        "### Updated APIs\n- Updated opensearch-php APIs to reflect [opensearch-api-specification@" . substr($latestCommitSha, 0, 7) . "]($commitUrl)",
                        $content
                    );

                    $result = file_put_contents($changelogPath, $fileContent);
                    if ($result === false) {
                        throw new \RuntimeException('Failed to write to CHANGELOG.md');
                    }
                } else {
                    throw new \RuntimeException("'Updated APIs' section is not present in CHANGELOG.md");
                }
            }
        } else {
            echo "No changes detected\n";
        }

    } catch (\Exception $e) {
        echo "Error occurred: " . $e->getMessage() . "\n";
    }
}

main();
