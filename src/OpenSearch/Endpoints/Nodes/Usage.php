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
 * Class Usage
 * Elasticsearch API name nodes.usage
 *
 */
class Usage extends AbstractEndpoint
{
    protected $node_id;
    protected $metric;

    public function getURI(): string
    {
        $node_id = $this->node_id ?? null;
        $metric = $this->metric ?? null;

        if (isset($node_id) && isset($metric)) {
            return "/_nodes/$node_id/usage/$metric";
        }
        if (isset($node_id)) {
            return "/_nodes/$node_id/usage";
        }
        if (isset($metric)) {
            return "/_nodes/usage/$metric";
        }
        return "/_nodes/usage";
    }

    public function getParamWhitelist(): array
    {
        return [
            'timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setNodeId($node_id): Usage
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

    public function setMetric($metric): Usage
    {
        if (isset($metric) !== true) {
            return $this;
        }
        if (is_array($metric) === true) {
            $metric = implode(",", $metric);
        }
        $this->metric = $metric;

        return $this;
    }
}
