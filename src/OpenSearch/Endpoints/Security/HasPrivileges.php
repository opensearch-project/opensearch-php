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

class HasPrivileges extends AbstractEndpoint
{
    protected $user;

    public function getURI(): string
    {
        $user = $this->user ?? null;

        if (isset($user)) {
            return "/_security/user/$user/_has_privileges";
        }
        return "/_security/user/_has_privileges";
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return isset($this->body) ? 'POST' : 'GET';
    }

    public function setBody($body): HasPrivileges
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setUser($user): HasPrivileges
    {
        if (isset($user) !== true) {
            return $this;
        }
        $this->user = $user;

        return $this;
    }
}
