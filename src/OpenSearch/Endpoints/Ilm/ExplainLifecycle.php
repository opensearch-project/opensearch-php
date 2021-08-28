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

namespace OpenSearch\Endpoints\Ilm;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class ExplainLifecycle
 * Elasticsearch API name ilm.explain_lifecycle
 *
 */
class ExplainLifecycle extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/$index/_ilm/explain";
        }
        throw new RuntimeException('Missing parameter for the endpoint ilm.explain_lifecycle');
    }

    public function getParamWhitelist(): array
    {
        return [
            'only_managed',
            'only_errors'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
