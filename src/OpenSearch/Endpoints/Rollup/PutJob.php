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

namespace OpenSearch\Endpoints\Rollup;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class PutJob
 * Elasticsearch API name rollup.put_job
 *
 */
class PutJob extends AbstractEndpoint
{
    public function getURI(): string
    {
        $id = $this->id ?? null;

        if (isset($id)) {
            return "/_rollup/job/$id";
        }
        throw new RuntimeException('Missing parameter for the endpoint rollup.put_job');
    }

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): PutJob
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
