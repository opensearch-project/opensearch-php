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

namespace OpenSearch\Endpoints\MachineLearning\Models;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class UndeployModel extends AbstractEndpoint
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
            return "/_plugins/_ml/models/$this->id/_undeploy";
        }

        throw new RuntimeException(
            'id is required for undeploy'
        );

    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'POST';
    }
}
