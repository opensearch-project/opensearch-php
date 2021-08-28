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

namespace OpenSearch\Endpoints\Indices;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class AddBlock
 * Elasticsearch API name indices.add_block
 *
 */
class AddBlock extends AbstractEndpoint
{
    protected $block;

    public function getURI(): string
    {
        $index = $this->index ?? null;
        $block = $this->block ?? null;

        if (isset($index) && isset($block)) {
            return "/$index/_block/$block";
        }
        throw new RuntimeException('Missing parameter for the endpoint indices.add_block');
    }

    public function getParamWhitelist(): array
    {
        return [
            'timeout',
            'master_timeout',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBlock($block): AddBlock
    {
        if (isset($block) !== true) {
            return $this;
        }
        $this->block = $block;

        return $this;
    }
}
