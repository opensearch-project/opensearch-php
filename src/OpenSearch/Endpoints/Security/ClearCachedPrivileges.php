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

/**
 * Class ClearCachedPrivileges
 * Elasticsearch API name security.clear_cached_privileges
 *
 */
class ClearCachedPrivileges extends AbstractEndpoint
{
    protected $application;

    public function getURI(): string
    {
        $application = $this->application ?? null;

        if (isset($application)) {
            return "/_security/privilege/$application/_clear_cache";
        }
        throw new RuntimeException('Missing parameter for the endpoint security.clear_cached_privileges');
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setApplication($application): ClearCachedPrivileges
    {
        if (isset($application) !== true) {
            return $this;
        }
        if (is_array($application) === true) {
            $application = implode(",", $application);
        }
        $this->application = $application;

        return $this;
    }
}
