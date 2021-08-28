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
 * Class ClearCachedRealms
 * Elasticsearch API name security.clear_cached_realms
 *
 */
class ClearCachedRealms extends AbstractEndpoint
{
    protected $realms;

    public function getURI(): string
    {
        $realms = $this->realms ?? null;

        if (isset($realms)) {
            return "/_security/realm/$realms/_clear_cache";
        }
        throw new RuntimeException('Missing parameter for the endpoint security.clear_cached_realms');
    }

    public function getParamWhitelist(): array
    {
        return [
            'usernames'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setRealms($realms): ClearCachedRealms
    {
        if (isset($realms) !== true) {
            return $this;
        }
        if (is_array($realms) === true) {
            $realms = implode(",", $realms);
        }
        $this->realms = $realms;

        return $this;
    }
}
