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
 * Class AckWatch
 * Elasticsearch API name watcher.ack_watch
 *
 */
class AckWatch extends AbstractEndpoint
{
    protected $watch_id;
    protected $action_id;

    public function getURI(): string
    {
        if (isset($this->watch_id) !== true) {
            throw new RuntimeException(
                'watch_id is required for ack_watch'
            );
        }
        $watch_id = $this->watch_id;
        $action_id = $this->action_id ?? null;

        if (isset($action_id)) {
            return "/_watcher/watch/$watch_id/_ack/$action_id";
        }
        return "/_watcher/watch/$watch_id/_ack";
    }

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setWatchId($watch_id): AckWatch
    {
        if (isset($watch_id) !== true) {
            return $this;
        }
        $this->watch_id = $watch_id;

        return $this;
    }

    public function setActionId($action_id): AckWatch
    {
        if (isset($action_id) !== true) {
            return $this;
        }
        if (is_array($action_id) === true) {
            $action_id = implode(",", $action_id);
        }
        $this->action_id = $action_id;

        return $this;
    }
}
