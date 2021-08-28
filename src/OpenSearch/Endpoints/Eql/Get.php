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
 * Class Get
 * Elasticsearch API name eql.get
 *
 */
class Get extends AbstractEndpoint
{
    public function getURI(): string
    {
        $id = $this->id ?? null;

        if (isset($id)) {
            return "/_eql/search/$id";
        }
        throw new RuntimeException('Missing parameter for the endpoint eql.get');
    }

    public function getParamWhitelist(): array
    {
        return [
            'wait_for_completion_timeout',
            'keep_alive'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
