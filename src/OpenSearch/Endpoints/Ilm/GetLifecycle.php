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

namespace OpenSearch\Endpoints\Ilm;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class GetLifecycle
 * Elasticsearch API name ilm.get_lifecycle
 *
 */
class GetLifecycle extends AbstractEndpoint
{
    protected $policy;

    public function getURI(): string
    {
        $policy = $this->policy ?? null;

        if (isset($policy)) {
            return "/_ilm/policy/$policy";
        }
        return "/_ilm/policy";
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

    public function setPolicy($policy): GetLifecycle
    {
        if (isset($policy) !== true) {
            return $this;
        }
        $this->policy = $policy;

        return $this;
    }
}
