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
 * Class Analyze
 * Elasticsearch API name indices.analyze
 *
 */
class Analyze extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/$index/_analyze";
        }
        return "/_analyze";
    }

    public function getParamWhitelist(): array
    {
        return [
            'index'
        ];
    }

    public function getMethod(): string
    {
        return isset($this->body) ? 'POST' : 'GET';
    }

    public function setBody($body): Analyze
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
