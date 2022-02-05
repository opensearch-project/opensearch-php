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

class CreateTenant extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $tenant;

    public function getParamWhitelist(): array
    {
        return ['description'];
    }

    public function getURI(): string
    {
        if (!isset($this->tenant)) {
            throw new RuntimeException('Missing parameter for the endpoint security.create_tenant');
        }

        return "/_plugins/_security/api/tenants/$this->tenant";
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * @param string|null $tenant
     * @return CreateTenant
     */
    public function setTenant(?string $tenant): CreateTenant
    {
        $this->tenant = $tenant;
        return $this;
    }
}
