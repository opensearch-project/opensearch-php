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

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class GetLifecycle
 * Elasticsearch API name slm.get_lifecycle
 *
 */
class GetLifecycle extends AbstractEndpoint
{
    protected $policy_id;

    public function getURI(): string
    {
        $policy_id = $this->policy_id ?? null;

        if (isset($policy_id)) {
            return "/_slm/policy/$policy_id";
        }
        return "/_slm/policy";
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setPolicyId($policy_id): GetLifecycle
    {
        if (isset($policy_id) !== true) {
            return $this;
        }
        if (is_array($policy_id) === true) {
            $policy_id = implode(",", $policy_id);
        }
        $this->policy_id = $policy_id;

        return $this;
    }
}
