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

namespace OpenSearch\Endpoints\Slm;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class ExecuteLifecycle
 * Elasticsearch API name slm.execute_lifecycle
 *
 */
class ExecuteLifecycle extends AbstractEndpoint
{
    protected $policy_id;

    public function getURI(): string
    {
        $policy_id = $this->policy_id ?? null;

        if (isset($policy_id)) {
            return "/_slm/policy/$policy_id/_execute";
        }
        throw new RuntimeException('Missing parameter for the endpoint slm.execute_lifecycle');
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setPolicyId($policy_id): ExecuteLifecycle
    {
        if (isset($policy_id) !== true) {
            return $this;
        }
        $this->policy_id = $policy_id;

        return $this;
    }
}
