<?php
/**
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
declare(strict_types = 1);

namespace OpenSearch\Endpoints\Snapshot;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class CleanupRepository
 * Elasticsearch API name snapshot.cleanup_repository
 *
 */
class CleanupRepository extends AbstractEndpoint
{
    protected $repository;

    public function getURI(): string
    {
        $repository = $this->repository ?? null;

        if (isset($repository)) {
            return "/_snapshot/$repository/_cleanup";
        }
        throw new RuntimeException('Missing parameter for the endpoint snapshot.cleanup_repository');
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout',
            'timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setRepository($repository): CleanupRepository
    {
        if (isset($repository) !== true) {
            return $this;
        }
        $this->repository = $repository;

        return $this;
    }
}