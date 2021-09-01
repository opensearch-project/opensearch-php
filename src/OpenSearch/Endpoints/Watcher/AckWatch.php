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

namespace OpenSearch\Endpoints\Watcher;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

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
