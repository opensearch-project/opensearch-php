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

namespace OpenSearch\Endpoints\Cluster;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class DeleteVotingConfigExclusions
 * Elasticsearch API name cluster.delete_voting_config_exclusions
 *
 */
class DeleteVotingConfigExclusions extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_cluster/voting_config_exclusions";
    }

    public function getParamWhitelist(): array
    {
        return [
            'wait_for_removal'
        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }
}
