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

namespace OpenSearch\Endpoints\Security;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class GetTenant extends AbstractEndpoint
{
    protected $tenant;

    public function getURI(): string
    {
        if (isset($this->tenant) !== true) {
            throw new RuntimeException(
                'tenant is required for get_tenant'
            );
        }
        $tenant = $this->tenant;

        return "/_plugins/_security/api/tenants/$tenant";
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

    public function setTenant($tenant): GetTenant
    {
        if (isset($tenant) !== true) {
            return $this;
        }
        $this->tenant = $tenant;

        return $this;
    }
}
