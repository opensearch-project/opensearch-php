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

namespace OpenSearch\Endpoints\Snapshot;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class CreateRepository
 * Elasticsearch API name snapshot.create_repository
 *
 */
class CreateRepository extends AbstractEndpoint
{
    protected $repository;

    public function getURI(): string
    {
        $repository = $this->repository ?? null;

        if (isset($repository)) {
            return "/_snapshot/$repository";
        }
        throw new RuntimeException('Missing parameter for the endpoint snapshot.create_repository');
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout',
            'timeout',
            'verify'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): CreateRepository
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setRepository($repository): CreateRepository
    {
        if (isset($repository) !== true) {
            return $this;
        }
        $this->repository = $repository;

        return $this;
    }
}
