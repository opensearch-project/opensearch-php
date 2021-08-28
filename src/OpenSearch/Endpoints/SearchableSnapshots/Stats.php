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
 * Class Stats
 * Elasticsearch API name searchable_snapshots.stats
 *
 */
class Stats extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/$index/_searchable_snapshots/stats";
        }
        return "/_searchable_snapshots/stats";
    }

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
