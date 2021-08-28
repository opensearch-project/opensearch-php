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

/**
 * Class Tasks
 * Elasticsearch API name cat.tasks
 *
 */
class Tasks extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_cat/tasks";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'node_id',
            'actions',
            'detailed',
            'parent_task',
            'h',
            'help',
            's',
            'time',
            'v'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
