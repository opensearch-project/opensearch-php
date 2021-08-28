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

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class Status
 * Elasticsearch API name snapshot.status
 *
 */
class Status extends AbstractEndpoint
{
    protected $repository;
    protected $snapshot;

    public function getURI(): string
    {
        $repository = $this->repository ?? null;
        $snapshot = $this->snapshot ?? null;

        if (isset($repository) && isset($snapshot)) {
            return "/_snapshot/$repository/$snapshot/_status";
        }
        if (isset($repository)) {
            return "/_snapshot/$repository/_status";
        }
        return "/_snapshot/_status";
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout',
            'ignore_unavailable'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setRepository($repository): Status
    {
        if (isset($repository) !== true) {
            return $this;
        }
        $this->repository = $repository;

        return $this;
    }

    public function setSnapshot($snapshot): Status
    {
        if (isset($snapshot) !== true) {
            return $this;
        }
        if (is_array($snapshot) === true) {
            $snapshot = implode(",", $snapshot);
        }
        $this->snapshot = $snapshot;

        return $this;
    }
}
