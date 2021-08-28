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

namespace OpenSearch\Endpoints\Indices;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class Recovery
 * Elasticsearch API name indices.recovery
 *
 */
class Recovery extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/$index/_recovery";
        }
        return "/_recovery";
    }

    public function getParamWhitelist(): array
    {
        return [
            'detailed',
            'active_only'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
