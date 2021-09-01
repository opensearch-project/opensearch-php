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

class DeleteDanglingIndex extends AbstractEndpoint
{
    protected $index_uuid;

    public function getURI(): string
    {
        $index_uuid = $this->index_uuid ?? null;

        if (isset($index_uuid)) {
            return "/_dangling/$index_uuid";
        }
        throw new RuntimeException('Missing parameter for the endpoint dangling_indices.delete_dangling_index');
    }

    public function getParamWhitelist(): array
    {
        return [
            'accept_data_loss',
            'timeout',
            'master_timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function setIndexUuid($index_uuid): DeleteDanglingIndex
    {
        if (isset($index_uuid) !== true) {
            return $this;
        }
        $this->index_uuid = $index_uuid;

        return $this;
    }
}
