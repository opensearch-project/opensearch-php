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

class PatchTenants extends AbstractEndpoint
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
        return 'PATCH';
    }

    /**
     * @param string|null $tenant
     * @return PatchTenants
     */
    public function setTenant(?string $tenant): PatchTenants
    {
        $this->tenant = $tenant;
        return $this;
    }
}
