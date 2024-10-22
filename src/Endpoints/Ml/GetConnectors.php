<?php

declare(strict_types=1);

/**
 *  Copyright OpenSearch Contributors
 *   SPDX-License-Identifier: Apache-2.0
 *
 *   The OpenSearch Contributors require contributions made to
 *   this file be licensed under the Apache-2.0 license or a
 *   compatible open source license.
 */

namespace OpenSearch\Endpoints\Ml;

use OpenSearch\Endpoints\AbstractEndpoint;

class GetConnectors extends AbstractEndpoint
{
    /**
     * @return string[]
     */
    public function getParamWhitelist(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getURI(): string
    {
        return '/_plugins/_ml/connectors/_search';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'POST';
    }
}
