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

namespace OpenSearch\Endpoints\Watcher;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class ActivateWatch
 * Elasticsearch API name watcher.activate_watch
 *
 */
class ActivateWatch extends AbstractEndpoint
{
    protected $watch_id;

    public function getURI(): string
    {
        $watch_id = $this->watch_id ?? null;

        if (isset($watch_id)) {
            return "/_watcher/watch/$watch_id/_activate";
        }
        throw new RuntimeException('Missing parameter for the endpoint watcher.activate_watch');
    }

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setWatchId($watch_id): ActivateWatch
    {
        if (isset($watch_id) !== true) {
            return $this;
        }
        $this->watch_id = $watch_id;

        return $this;
    }
}
