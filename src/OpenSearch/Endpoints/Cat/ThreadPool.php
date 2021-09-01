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

class ThreadPool extends AbstractEndpoint
{
    protected $thread_pool_patterns;

    public function getURI(): string
    {
        $thread_pool_patterns = $this->thread_pool_patterns ?? null;

        if (isset($thread_pool_patterns)) {
            return "/_cat/thread_pool/$thread_pool_patterns";
        }
        return "/_cat/thread_pool";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'size',
            'local',
            'master_timeout',
            'h',
            'help',
            's',
            'v'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setThreadPoolPatterns($thread_pool_patterns): ThreadPool
    {
        if (isset($thread_pool_patterns) !== true) {
            return $this;
        }
        if (is_array($thread_pool_patterns) === true) {
            $thread_pool_patterns = implode(",", $thread_pool_patterns);
        }
        $this->thread_pool_patterns = $thread_pool_patterns;

        return $this;
    }
}
