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

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class SimulateIndexTemplate
 * Elasticsearch API name indices.simulate_index_template
 *
 */
class SimulateIndexTemplate extends AbstractEndpoint
{
    protected $name;

    public function getURI(): string
    {
        $name = $this->name ?? null;

        if (isset($name)) {
            return "/_index_template/_simulate_index/$name";
        }
        throw new RuntimeException('Missing parameter for the endpoint indices.simulate_index_template');
    }

    public function getParamWhitelist(): array
    {
        return [
            'create',
            'cause',
            'master_timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): SimulateIndexTemplate
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setName($name): SimulateIndexTemplate
    {
        if (isset($name) !== true) {
            return $this;
        }
        $this->name = $name;

        return $this;
    }
}
