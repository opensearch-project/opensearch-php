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

class GetDecommissionAwareness extends AbstractEndpoint
{
    protected $awareness_attribute_name;

    public function getURI(): string
    {
        if (isset($this->awareness_attribute_name) !== true) {
            throw new RuntimeException(
                'awareness_attribute_name is required for get_decommission_awareness'
            );
        }
        $awareness_attribute_name = $this->awareness_attribute_name;

        return "/_cluster/decommission/awareness/$awareness_attribute_name/_status";
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

    public function setAwarenessAttributeName($awareness_attribute_name): GetDecommissionAwareness
    {
        if (isset($awareness_attribute_name) !== true) {
            return $this;
        }
        $this->awareness_attribute_name = $awareness_attribute_name;

        return $this;
    }
}
