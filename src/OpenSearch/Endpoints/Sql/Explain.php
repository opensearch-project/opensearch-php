<?php

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

namespace OpenSearch\Endpoints\Sql;

use OpenSearch\Endpoints\AbstractEndpoint;

class Explain extends AbstractEndpoint
{
    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getURI(): string
    {
        return '/_plugins/_sql/_explain';
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
