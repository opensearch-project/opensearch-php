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

namespace OpenSearch\Endpoints\Cat;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class Snapshots
 * Elasticsearch API name cat.snapshots
 *
 */
class Snapshots extends AbstractEndpoint
{
    protected $repository;

    public function getURI(): string
    {
        $repository = $this->repository ?? null;

        if (isset($repository)) {
            return "/_cat/snapshots/$repository";
        }
        return "/_cat/snapshots";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'ignore_unavailable',
            'master_timeout',
            'h',
            'help',
            's',
            'time',
            'v'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setRepository($repository): Snapshots
    {
        if (isset($repository) !== true) {
            return $this;
        }
        if (is_array($repository) === true) {
            $repository = implode(",", $repository);
        }
        $this->repository = $repository;

        return $this;
    }
}
