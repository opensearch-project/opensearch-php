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

namespace OpenSearch\Endpoints\License;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class PostStartTrial
 * Elasticsearch API name license.post_start_trial
 *
 */
class PostStartTrial extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_license/start_trial";
    }

    public function getParamWhitelist(): array
    {
        return [
            'type',
            'acknowledge'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
