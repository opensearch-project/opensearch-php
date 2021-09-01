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

namespace OpenSearch\Endpoints\Indices;

use OpenSearch\Endpoints\AbstractEndpoint;

class GetSettings extends AbstractEndpoint
{
    protected $name;

    public function getURI(): string
    {
        $index = $this->index ?? null;
        $name = $this->name ?? null;

        if (isset($index) && isset($name)) {
            return "/$index/_settings/$name";
        }
        if (isset($index)) {
            return "/$index/_settings";
        }
        if (isset($name)) {
            return "/_settings/$name";
        }
        return "/_settings";
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'flat_settings',
            'local',
            'include_defaults'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setName($name): GetSettings
    {
        if (isset($name) !== true) {
            return $this;
        }
        if (is_array($name) === true) {
            $name = implode(",", $name);
        }
        $this->name = $name;

        return $this;
    }
}
