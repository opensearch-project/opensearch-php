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

use OpenSearch\Endpoints\AbstractEndpoint;

class DeleteConfigs extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_plugins/_notifications/configs";
    }

    public function getParamWhitelist(): array
    {
        return [
            'config_id',
            'config_id_list',
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }
}
