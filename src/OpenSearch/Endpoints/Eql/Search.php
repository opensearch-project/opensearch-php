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

namespace OpenSearch\Endpoints\Eql;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class Search
 * Elasticsearch API name eql.search
 *
 */
class Search extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/$index/_eql/search";
        }
        throw new RuntimeException('Missing parameter for the endpoint eql.search');
    }

    public function getParamWhitelist(): array
    {
        return [
            'wait_for_completion_timeout',
            'keep_on_completion',
            'keep_alive'
        ];
    }

    public function getMethod(): string
    {
        return isset($this->body) ? 'POST' : 'GET';
    }

    public function setBody($body): Search
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
