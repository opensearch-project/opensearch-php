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

namespace OpenSearch\Endpoints\Cluster;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class GetComponentTemplate
 * Elasticsearch API name cluster.get_component_template
 *
 */
class GetComponentTemplate extends AbstractEndpoint
{
    protected $name;

    public function getURI(): string
    {
        $name = $this->name ?? null;

        if (isset($name)) {
            return "/_component_template/$name";
        }
        return "/_component_template";
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout',
            'local'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setName($name): GetComponentTemplate
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
