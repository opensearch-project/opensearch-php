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

namespace OpenSearch\Endpoints\Enrich;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class ExecutePolicy
 * Elasticsearch API name enrich.execute_policy
 *
 */
class ExecutePolicy extends AbstractEndpoint
{
    protected $name;

    public function getURI(): string
    {
        $name = $this->name ?? null;

        if (isset($name)) {
            return "/_enrich/policy/$name/_execute";
        }
        throw new RuntimeException('Missing parameter for the endpoint enrich.execute_policy');
    }

    public function getParamWhitelist(): array
    {
        return [
            'wait_for_completion'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setName($name): ExecutePolicy
    {
        if (isset($name) !== true) {
            return $this;
        }
        $this->name = $name;

        return $this;
    }
}
