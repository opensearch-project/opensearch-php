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

namespace OpenSearch\Endpoints\Cat;

use OpenSearch\Endpoints\AbstractEndpoint;

class SegmentReplication extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/_cat/segment_replication/$index";
        }
        return "/_cat/segment_replication";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'h',
            'help',
            's',
            'v',
            'allow_no_indices',
            'expand_wildcards',
            'ignore_throttled',
            'ignore_unavailable',
            'active_only',
            'completed_only',
            'bytes',
            'detailed',
            'shards',
            'index',
            'time',
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
}
