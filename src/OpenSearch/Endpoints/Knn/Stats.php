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

namespace OpenSearch\Endpoints\Knn;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class Stats extends AbstractEndpoint
{
    protected $node_id;
    protected $stat;

    public function getURI(): string
    {
        $node_id = $this->node_id ?? null;
        $stat = $this->stat ?? null;
        if (isset($node_id) && isset($stat)) {
            return "/_plugins/_knn/$node_id/stats/$stat";
        }
        if (isset($node_id)) {
            return "/_plugins/_knn/$node_id/stats";
        }
        if (isset($stat)) {
            return "/_plugins/_knn/stats/$stat";
        }
        return "/_plugins/_knn/stats";
    }

    public function getParamWhitelist(): array
    {
        return [
            'timeout',
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setNodeId($node_id): Stats
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

    public function setStat($stat): Stats
    {
        if (isset($stat) !== true) {
            return $this;
        }
        if (is_array($stat) === true) {
            $stat = implode(",", $stat);
        }
        $this->stat = $stat;

        return $this;
    }
}
