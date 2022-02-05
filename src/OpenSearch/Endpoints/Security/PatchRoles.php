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

class PatchRoles extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $role;

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getURI(): string
    {
        return '/_plugins/_security/api/roles' . ($this->role ? "/{$this->role}" : '');
    }

    public function getMethod(): string
    {
        return 'PATCH';
    }

    /**
     * @param string|null $role
     * @return PatchRoles
     */
    public function setRole(?string $role): PatchRoles
    {
        $this->role = $role;
        return $this;
    }
}
