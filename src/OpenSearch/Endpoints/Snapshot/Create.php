<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Endpoints\Snapshot;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class Create extends AbstractEndpoint
{
    protected $repository;
    protected $snapshot;

    public function getURI(): string
    {
        if (isset($this->repository) !== true) {
            throw new RuntimeException(
                'repository is required for create'
            );
        }
        $repository = $this->repository;
        if (isset($this->snapshot) !== true) {
            throw new RuntimeException(
                'snapshot is required for create'
            );
        }
        $snapshot = $this->snapshot;

        return "/_snapshot/$repository/$snapshot";
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout',
            'cluster_manager_timeout',
            'wait_for_completion',
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): Create
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setRepository($repository): Create
    {
        if (isset($repository) !== true) {
            return $this;
        }
        $this->repository = $repository;

        return $this;
    }

    public function setSnapshot($snapshot): Create
    {
        if (isset($snapshot) !== true) {
            return $this;
        }
        $this->snapshot = $snapshot;

        return $this;
    }

    protected function getParamDeprecation(): array
    {
        return ['master_timeout' => 'cluster_manager_timeout'];
    }
}
