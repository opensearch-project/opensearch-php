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

class CloneSnapshot extends AbstractEndpoint
{
    protected $repository;
    protected $snapshot;
    protected $target_snapshot;

    public function getURI(): string
    {
        $repository = $this->repository ?? null;
        $snapshot = $this->snapshot ?? null;
        $target_snapshot = $this->target_snapshot ?? null;

        if (isset($repository) && isset($snapshot) && isset($target_snapshot)) {
            return "/_snapshot/$repository/$snapshot/_clone/$target_snapshot";
        }
        throw new RuntimeException('Missing parameter for the endpoint snapshot.clone');
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): CloneSnapshot
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setRepository($repository): CloneSnapshot
    {
        if (isset($repository) !== true) {
            return $this;
        }
        $this->repository = $repository;

        return $this;
    }

    public function setSnapshot($snapshot): CloneSnapshot
    {
        if (isset($snapshot) !== true) {
            return $this;
        }
        $this->snapshot = $snapshot;

        return $this;
    }

    public function setTargetSnapshot($target_snapshot): CloneSnapshot
    {
        if (isset($target_snapshot) !== true) {
            return $this;
        }
        $this->target_snapshot = $target_snapshot;

        return $this;
    }
}
