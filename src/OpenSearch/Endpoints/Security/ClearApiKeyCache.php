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

class ClearApiKeyCache extends AbstractEndpoint
{
    protected $ids;

    public function getURI(): string
    {
        $ids = $this->ids ?? null;

        if (isset($ids)) {
            return "/_security/api_key/$ids/_clear_cache";
        }
        throw new RuntimeException('Missing parameter for the endpoint security.clear_api_key_cache');
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

    public function setIds($ids): ClearApiKeyCache
    {
        if (isset($ids) !== true) {
            return $this;
        }
        if (is_array($ids) === true) {
            $ids = implode(",", $ids);
        }
        $this->ids = $ids;

        return $this;
    }
}
