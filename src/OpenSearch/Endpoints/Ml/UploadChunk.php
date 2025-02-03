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

namespace OpenSearch\Endpoints\Ml;

use OpenSearch\Exception\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class UploadChunk extends AbstractEndpoint
{
    protected $chunk_number;
    protected $model_id;

    public function getURI(): string
    {
        $chunk_number = $this->chunk_number ?? null;
        $model_id = $this->model_id ?? null;
        if (isset($model_id) && isset($chunk_number)) {
            return "/_plugins/_ml/models/$model_id/upload_chunk/$chunk_number";
        }
        throw new RuntimeException('Missing parameter for the endpoint ml.upload_chunk');
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
        return 'POST';
    }

    public function setBody($body): static
    {
        if (is_null($body)) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setChunkNumber($chunk_number): static
    {
        if (is_null($chunk_number)) {
            return $this;
        }
        $this->chunk_number = $chunk_number;

        return $this;
    }

    public function setModelId($model_id): static
    {
        if (is_null($model_id)) {
            return $this;
        }
        $this->model_id = $model_id;

        return $this;
    }
}
