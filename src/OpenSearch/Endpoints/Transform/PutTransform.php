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

namespace OpenSearch\Endpoints\Transform;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class PutTransform extends AbstractEndpoint
{
    protected $transform_id;

    public function getURI(): string
    {
        $transform_id = $this->transform_id ?? null;

        if (isset($transform_id)) {
            return "/_transform/$transform_id";
        }
        throw new RuntimeException('Missing parameter for the endpoint transform.put_transform');
    }

    public function getParamWhitelist(): array
    {
        return [
            'defer_validation'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): PutTransform
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setTransformId($transform_id): PutTransform
    {
        if (isset($transform_id) !== true) {
            return $this;
        }
        $this->transform_id = $transform_id;

        return $this;
    }
}
