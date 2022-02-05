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

class CreateUser extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $username;

    public function getParamWhitelist(): array
    {
        return [
            'password',
            'opendistro_security_roles',
            'backend_roles',
            'attributes',
        ];
    }

    public function getURI(): string
    {
        if (!isset($this->username)) {
            throw new RuntimeException('Missing parameter for the endpoint security.create_user');
        }

        return "/_plugins/_security/api/internalusers/$this->username";
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * @param string|null $username
     * @return CreateUser
     */
    public function setUsername(?string $username): CreateUser
    {
        $this->username = $username;
        return $this;
    }
}
