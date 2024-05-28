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

class PatchRole extends AbstractEndpoint
{
    protected $role;

    public function getURI(): string
    {
        if (isset($this->role) !== true) {
            throw new RuntimeException(
                'role is required for patch_role'
            );
        }
        $role = $this->role;

        return "/_plugins/_security/api/roles/$role";
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
        return 'PATCH';
    }

    public function setBody($body): PatchRole
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setRole($role): PatchRole
    {
        if (isset($role) !== true) {
            return $this;
        }
        $this->role = $role;

        return $this;
    }
}
