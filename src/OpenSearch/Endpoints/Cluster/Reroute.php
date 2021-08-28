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
 * Class Reroute
 * Elasticsearch API name cluster.reroute
 *
 */
class Reroute extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_cluster/reroute";
    }

    public function getParamWhitelist(): array
    {
        return [
            'dry_run',
            'explain',
            'retry_failed',
            'metric',
            'master_timeout',
            'timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): Reroute
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
