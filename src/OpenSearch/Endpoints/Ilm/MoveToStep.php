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

namespace OpenSearch\Endpoints\Ilm;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class MoveToStep
 * Elasticsearch API name ilm.move_to_step
 *
 */
class MoveToStep extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/_ilm/move/$index";
        }
        throw new RuntimeException('Missing parameter for the endpoint ilm.move_to_step');
    }

    public function getParamWhitelist(): array
    {
        return [

        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): MoveToStep
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
