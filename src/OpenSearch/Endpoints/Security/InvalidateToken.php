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
 * Class InvalidateToken
 * Elasticsearch API name security.invalidate_token
 *
 */
class InvalidateToken extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_security/oauth2/token";
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function setBody($body): InvalidateToken
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
