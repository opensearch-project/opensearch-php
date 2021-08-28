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

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class DeleteLifecycle
 * Elasticsearch API name ilm.delete_lifecycle
 *
 */
class DeleteLifecycle extends AbstractEndpoint
{
    protected $policy;

    public function getURI(): string
    {
        $policy = $this->policy ?? null;

        if (isset($policy)) {
            return "/_ilm/policy/$policy";
        }
        throw new RuntimeException('Missing parameter for the endpoint ilm.delete_lifecycle');
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function setPolicy($policy): DeleteLifecycle
    {
        if (isset($policy) !== true) {
            return $this;
        }
        $this->policy = $policy;

        return $this;
    }
}
