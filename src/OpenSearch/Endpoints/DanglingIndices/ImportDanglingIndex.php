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

namespace OpenSearch\Endpoints\DanglingIndices;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class ImportDanglingIndex extends AbstractEndpoint
{
    protected $index_uuid;

    public function getURI(): string
    {
        if (isset($this->index_uuid) !== true) {
            throw new RuntimeException(
                'index_uuid is required for import_dangling_index'
            );
        }
        $index_uuid = $this->index_uuid;

        return "/_dangling/$index_uuid";
    }

    public function getParamWhitelist(): array
    {
        return [
            'accept_data_loss',
            'timeout',
            'master_timeout',
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
        return 'POST';
    }

    public function setIndexUuid($index_uuid): ImportDanglingIndex
    {
        if (isset($index_uuid) !== true) {
            return $this;
        }
        $this->index_uuid = $index_uuid;

        return $this;
    }

    protected function getParamDeprecation(): array
    {
        return ['master_timeout' => 'cluster_manager_timeout'];
    }
}
