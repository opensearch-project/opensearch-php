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

namespace OpenSearch\Endpoints\Sql;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class Query
 * Elasticsearch API name sql.query
 *
 */
class Query extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_sql";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format'
        ];
    }

    public function getMethod(): string
    {
        return isset($this->body) ? 'POST' : 'GET';
    }

    public function setBody($body): Query
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
