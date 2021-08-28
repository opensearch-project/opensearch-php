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

namespace OpenSearch\Endpoints\Nodes;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class HotThreads
 * Elasticsearch API name nodes.hot_threads
 *
 */
class HotThreads extends AbstractEndpoint
{
    protected $node_id;

    public function getURI(): string
    {
        $node_id = $this->node_id ?? null;

        if (isset($node_id)) {
            return "/_nodes/$node_id/hot_threads";
        }
        return "/_nodes/hot_threads";
    }

    public function getParamWhitelist(): array
    {
        return [
            'interval',
            'snapshots',
            'threads',
            'ignore_idle_threads',
            'type',
            'timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setNodeId($node_id): HotThreads
    {
        if (isset($node_id) !== true) {
            return $this;
        }
        if (is_array($node_id) === true) {
            $node_id = implode(",", $node_id);
        }
        $this->node_id = $node_id;

        return $this;
    }
}
