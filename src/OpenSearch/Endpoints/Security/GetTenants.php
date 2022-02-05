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

use OpenSearch\Endpoints\AbstractEndpoint;

class GetTenants extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $tenant;

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getURI(): string
    {
        return '/_plugins/_security/api/tenants' . ($this->tenant ? "/{$this->tenant}" : '');
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @param string|null $tenant
     * @return GetTenants
     */
    public function setTenant(?string $tenant): GetTenants
    {
        $this->tenant = $tenant;
        return $this;
    }
}
