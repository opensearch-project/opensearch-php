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

namespace OpenSearch\Endpoints\Transform;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class GetTransformStats
 * Elasticsearch API name transform.get_transform_stats
 *
 */
class GetTransformStats extends AbstractEndpoint
{
    protected $transform_id;

    public function getURI(): string
    {
        $transform_id = $this->transform_id ?? null;

        if (isset($transform_id)) {
            return "/_transform/$transform_id/_stats";
        }
        throw new RuntimeException('Missing parameter for the endpoint transform.get_transform_stats');
    }

    public function getParamWhitelist(): array
    {
        return [
            'from',
            'size',
            'allow_no_match'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setTransformId($transform_id): GetTransformStats
    {
        if (isset($transform_id) !== true) {
            return $this;
        }
        $this->transform_id = $transform_id;

        return $this;
    }
}
