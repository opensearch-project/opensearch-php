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

namespace OpenSearch\Endpoints;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class Reindex
 * Elasticsearch API name reindex
 *
 */
class Reindex extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_reindex";
    }

    public function getParamWhitelist(): array
    {
        return [
            'refresh',
            'timeout',
            'wait_for_active_shards',
            'wait_for_completion',
            'requests_per_second',
            'scroll',
            'slices',
            'max_docs'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): Reindex
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
