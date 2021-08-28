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

/**
 * Class PutPrivileges
 * Elasticsearch API name security.put_privileges
 *
 */
class PutPrivileges extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_security/privilege/";
    }

    public function getParamWhitelist(): array
    {
        return [
            'refresh'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): PutPrivileges
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
