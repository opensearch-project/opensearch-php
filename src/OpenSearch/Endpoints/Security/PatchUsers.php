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

class PatchUsers extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $username;

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getURI(): string
    {
        return '/_plugins/_security/api/internalusers' . ($this->username ? "/{$this->username}" : '');
    }

    public function getMethod(): string
    {
        return 'PATCH';
    }

    /**
     * @param string|null $username
     * @return PatchUsers
     */
    public function setUsername(?string $username): PatchUsers
    {
        $this->username = $username;
        return $this;
    }
}
