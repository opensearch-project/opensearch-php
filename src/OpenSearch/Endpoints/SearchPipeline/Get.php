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

namespace OpenSearch\Endpoints\SearchPipeline;

use OpenSearch\Endpoints\AbstractEndpoint;

class Get extends AbstractEndpoint
{
    public function getURI(): string
    {
        $id = $this->id ?? null;

        if (isset($id)) {
            return "/_search/pipeline/$id";
        }
        return "/_search/pipeline";
    }

    public function getParamWhitelist(): array
    {
        return [
            'cluster_manager_timeout',
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
