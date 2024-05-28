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

namespace OpenSearch\Endpoints\Cluster;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class GetWeightedRouting extends AbstractEndpoint
{
    protected $attribute;

    public function getURI(): string
    {
        if (isset($this->attribute) !== true) {
            throw new RuntimeException(
                'attribute is required for get_weighted_routing'
            );
        }
        $attribute = $this->attribute;

        return "/_cluster/routing/awareness/$attribute/weights";
    }

    public function getParamWhitelist(): array
    {
        return [
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setAttribute($attribute): GetWeightedRouting
    {
        if (isset($attribute) !== true) {
            return $this;
        }
        $this->attribute = $attribute;

        return $this;
    }
}
