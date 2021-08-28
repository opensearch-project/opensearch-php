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

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class ExistsSource
 * Elasticsearch API name exists_source
 *
 */
class ExistsSource extends AbstractEndpoint
{
    public function getURI(): string
    {
        if (isset($this->id) !== true) {
            throw new RuntimeException(
                'id is required for exists_source'
            );
        }
        $id = $this->id;
        if (isset($this->index) !== true) {
            throw new RuntimeException(
                'index is required for exists_source'
            );
        }
        $index = $this->index;
        $type = $this->type ?? null;
        if (isset($type)) {
            @trigger_error('Specifying types in urls has been deprecated', E_USER_DEPRECATED);
        }

        if (isset($type)) {
            return "/$index/$type/$id/_source";
        }
        return "/$index/_source/$id";
    }

    public function getParamWhitelist(): array
    {
        return [
            'preference',
            'realtime',
            'refresh',
            'routing',
            '_source',
            '_source_excludes',
            '_source_includes',
            'version',
            'version_type'
        ];
    }

    public function getMethod(): string
    {
        return 'HEAD';
    }
}
