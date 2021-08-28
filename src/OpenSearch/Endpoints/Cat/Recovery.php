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
 * Class Recovery
 * Elasticsearch API name cat.recovery
 *
 */
class Recovery extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/_cat/recovery/$index";
        }
        return "/_cat/recovery";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'active_only',
            'bytes',
            'detailed',
            'h',
            'help',
            'index',
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
