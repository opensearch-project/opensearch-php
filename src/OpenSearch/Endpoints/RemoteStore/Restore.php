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

namespace OpenSearch\Endpoints\RemoteStore;

use OpenSearch\Endpoints\AbstractEndpoint;

class Restore extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_remotestore/_restore";
    }

    public function getParamWhitelist(): array
    {
        return [
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
        return 'POST';
    }

    public function setBody($body): Restore
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
