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
 * Class Stats
 * Elasticsearch API name nodes.stats
 *
 */
class Stats extends AbstractEndpoint
{
    protected $node_id;
    protected $metric;
    protected $index_metric;

    public function getURI(): string
    {
        $node_id = $this->node_id ?? null;
        $metric = $this->metric ?? null;
        $index_metric = $this->index_metric ?? null;

        if (isset($node_id) && isset($metric) && isset($index_metric)) {
            return "/_nodes/$node_id/stats/$metric/$index_metric";
        }
        if (isset($metric) && isset($index_metric)) {
            return "/_nodes/stats/$metric/$index_metric";
        }
        if (isset($node_id) && isset($metric)) {
            return "/_nodes/$node_id/stats/$metric";
        }
        if (isset($node_id)) {
            return "/_nodes/$node_id/stats";
        }
        if (isset($metric)) {
            return "/_nodes/stats/$metric";
        }
        return "/_nodes/stats";
    }

    public function getParamWhitelist(): array
    {
        return [
            'completion_fields',
            'fielddata_fields',
            'fields',
            'groups',
            'level',
            'types',
            'timeout',
            'include_segment_file_sizes'
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

    public function setMetric($metric): Stats
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

    public function setIndexMetric($index_metric): Stats
    {
        if (isset($index_metric) !== true) {
            return $this;
        }
        if (is_array($index_metric) === true) {
            $index_metric = implode(",", $index_metric);
        }
        $this->index_metric = $index_metric;

        return $this;
    }
}
