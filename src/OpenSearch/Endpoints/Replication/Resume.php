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

namespace OpenSearch\Endpoints\Replication;

use OpenSearch\Exception\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class Resume extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;
        if (isset($index)) {
            return "/_plugins/_replication/$index/_resume";
        }
        throw new RuntimeException('Missing parameter for the endpoint replication.resume');
    }

    public function getParamWhitelist(): array
    {
        return [
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): static
    {
        if (is_null($body)) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
