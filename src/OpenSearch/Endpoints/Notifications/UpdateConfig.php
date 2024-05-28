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

namespace OpenSearch\Endpoints\Notifications;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class UpdateConfig extends AbstractEndpoint
{
    protected $config_id;

    public function getURI(): string
    {
        if (isset($this->config_id) !== true) {
            throw new RuntimeException(
                'config_id is required for update_config'
            );
        }
        $config_id = $this->config_id;

        return "/_plugins/_notifications/configs/$config_id";
    }

    public function getParamWhitelist(): array
    {
        return [
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

    public function setBody($body): UpdateConfig
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setConfigId($config_id): UpdateConfig
    {
        if (isset($config_id) !== true) {
            return $this;
        }
        $this->config_id = $config_id;

        return $this;
    }
}
