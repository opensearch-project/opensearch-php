<?php

declare(strict_types=1);

/**
 *  Copyright OpenSearch Contributors
 *   SPDX-License-Identifier: Apache-2.0
 *
 *   The OpenSearch Contributors require contributions made to
 *   this file be licensed under the Apache-2.0 license or a
 *   compatible open source license.
 */

namespace OpenSearch\Endpoints\MachineLearning\ModelGroups;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class DeleteModelGroup extends AbstractEndpoint
{
    /**
     * @return string[]
     */
    public function getParamWhitelist(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getURI(): string
    {
        if ($this->id) {
            return "/_plugins/_ml/model_groups/$this->id";
        }

        throw new RuntimeException(
            'id is required for delete'
        );

    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'DELETE';
    }
}
