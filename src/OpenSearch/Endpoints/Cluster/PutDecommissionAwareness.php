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

class PutDecommissionAwareness extends AbstractEndpoint
{
    protected $awareness_attribute_name;
    protected $awareness_attribute_value;

    public function getURI(): string
    {
        if (isset($this->awareness_attribute_name) !== true) {
            throw new RuntimeException(
                'awareness_attribute_name is required for put_decommission_awareness'
            );
        }
        $awareness_attribute_name = $this->awareness_attribute_name;
        if (isset($this->awareness_attribute_value) !== true) {
            throw new RuntimeException(
                'awareness_attribute_value is required for put_decommission_awareness'
            );
        }
        $awareness_attribute_value = $this->awareness_attribute_value;

        return "/_cluster/decommission/awareness/$awareness_attribute_name/$awareness_attribute_value";
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
        return 'PUT';
    }

    public function setAwarenessAttributeName($awareness_attribute_name): PutDecommissionAwareness
    {
        if (isset($awareness_attribute_name) !== true) {
            return $this;
        }
        $this->awareness_attribute_name = $awareness_attribute_name;

        return $this;
    }

    public function setAwarenessAttributeValue($awareness_attribute_value): PutDecommissionAwareness
    {
        if (isset($awareness_attribute_value) !== true) {
            return $this;
        }
        $this->awareness_attribute_value = $awareness_attribute_value;

        return $this;
    }
}
