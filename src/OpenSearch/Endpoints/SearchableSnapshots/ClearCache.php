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

namespace OpenSearch\Endpoints\SearchableSnapshots;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class ClearCache
 * Elasticsearch API name searchable_snapshots.clear_cache
 *
 */
class ClearCache extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/$index/_searchable_snapshots/cache/clear";
        }
        return "/_searchable_snapshots/cache/clear";
    }

    public function getParamWhitelist(): array
    {
        return [
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'index'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
