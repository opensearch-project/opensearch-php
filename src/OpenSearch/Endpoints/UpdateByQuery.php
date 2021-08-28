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

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class UpdateByQuery
 * Elasticsearch API name update_by_query
 *
 */
class UpdateByQuery extends AbstractEndpoint
{
    public function getURI(): string
    {
        if (isset($this->index) !== true) {
            throw new RuntimeException(
                'index is required for update_by_query'
            );
        }
        $index = $this->index;
        $type = $this->type ?? null;
        if (isset($type)) {
            @trigger_error('Specifying types in urls has been deprecated', E_USER_DEPRECATED);
        }

        if (isset($type)) {
            return "/$index/$type/_update_by_query";
        }
        return "/$index/_update_by_query";
    }

    public function getParamWhitelist(): array
    {
        return [
            'analyzer',
            'analyze_wildcard',
            'default_operator',
            'df',
            'from',
            'ignore_unavailable',
            'allow_no_indices',
            'conflicts',
            'expand_wildcards',
            'lenient',
            'pipeline',
            'preference',
            'q',
            'routing',
            'scroll',
            'search_type',
            'search_timeout',
            'size',
            'max_docs',
            'sort',
            '_source',
            '_source_excludes',
            '_source_includes',
            'terminate_after',
            'stats',
            'version',
            'version_type',
            'request_cache',
            'refresh',
            'timeout',
            'wait_for_active_shards',
            'scroll_size',
            'wait_for_completion',
            'requests_per_second',
            'slices'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): UpdateByQuery
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
