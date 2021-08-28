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

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class ExecuteWatch
 * Elasticsearch API name watcher.execute_watch
 *
 */
class ExecuteWatch extends AbstractEndpoint
{
    public function getURI(): string
    {
        $id = $this->id ?? null;

        if (isset($id)) {
            return "/_watcher/watch/$id/_execute";
        }
        return "/_watcher/watch/_execute";
    }

    public function getParamWhitelist(): array
    {
        return [
            'debug'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): ExecuteWatch
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
