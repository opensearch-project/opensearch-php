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

class CreateRole extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $role;

    public function getParamWhitelist(): array
    {
        return [
            'cluster_permissions',
            'index_permissions',
            'tenant_permissions',
        ];
    }

    public function getURI(): string
    {
        if (!isset($this->role)) {
            throw new RuntimeException('Missing parameter for the endpoint security.create_role');
        }

        return "/_plugins/_security/api/roles/$this->role";
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * @param string|null $role
     * @return CreateRole
     */
    public function setRole(?string $role): CreateRole
    {
        $this->role = $role;
        return $this;
    }
}
