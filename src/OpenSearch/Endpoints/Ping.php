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

namespace OpenSearch\Endpoints;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class Ping
 * Elasticsearch API name ping
 *
 */
class Ping extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/";
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'HEAD';
    }
}
