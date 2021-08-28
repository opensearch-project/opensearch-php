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
 * Class GetUser
 * Elasticsearch API name security.get_user
 *
 */
class GetUser extends AbstractEndpoint
{
    protected $username;

    public function getURI(): string
    {
        $username = $this->username ?? null;

        if (isset($username)) {
            return "/_security/user/$username";
        }
        return "/_security/user";
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setUsername($username): GetUser
    {
        if (isset($username) !== true) {
            return $this;
        }
        if (is_array($username) === true) {
            $username = implode(",", $username);
        }
        $this->username = $username;

        return $this;
    }
}
