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

namespace OpenSearch\Endpoints\Knn;

use OpenSearch\Endpoints\AbstractEndpoint;

class TrainModel extends AbstractEndpoint
{
    protected $model_id;

    public function getURI(): string
    {
        $model_id = $this->model_id ?? null;

        if (isset($model_id)) {
            return "/_plugins/_knn/models/$model_id/_train";
        }
        return "/_plugins/_knn/models/_train";
    }

    public function getParamWhitelist(): array
    {
        return [
            'preference',
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

    public function setBody($body): TrainModel
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setModelId($model_id): TrainModel
    {
        if (isset($model_id) !== true) {
            return $this;
        }
        $this->model_id = $model_id;

        return $this;
    }
}
