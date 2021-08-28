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
 * Class ReindexRethrottle
 * Elasticsearch API name reindex_rethrottle
 *
 */
class ReindexRethrottle extends AbstractEndpoint
{
    protected $task_id;

    public function getURI(): string
    {
        $task_id = $this->task_id ?? null;

        if (isset($task_id)) {
            return "/_reindex/$task_id/_rethrottle";
        }
        throw new RuntimeException('Missing parameter for the endpoint reindex_rethrottle');
    }

    public function getParamWhitelist(): array
    {
        return [
            'requests_per_second'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setTaskId($task_id): ReindexRethrottle
    {
        if (isset($task_id) !== true) {
            return $this;
        }
        $this->task_id = $task_id;

        return $this;
    }
}
