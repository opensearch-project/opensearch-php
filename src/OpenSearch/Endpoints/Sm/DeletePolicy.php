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

namespace OpenSearch\Endpoints\Sm;

use OpenSearch\Exception\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class DeletePolicy extends AbstractEndpoint
{
    protected $policy_name;

    public function getURI(): string
    {
        $policy_name = $this->policy_name ?? null;
        if (isset($policy_name)) {
            return '/_plugins/_sm/policies/' . rawurlencode($policy_name);
        }
        throw new RuntimeException('Missing parameter for the endpoint sm.delete_policy');
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
        return 'DELETE';
    }

    public function setPolicyName($policy_name): static
    {
        if (is_null($policy_name)) {
            return $this;
        }
        $this->policy_name = $policy_name;

        return $this;
    }
}
