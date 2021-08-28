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

namespace OpenSearch\Endpoints\DataFrameTransformDeprecated;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class PreviewTransform
 * Elasticsearch API name data_frame_transform_deprecated.preview_transform
 *
 */
class PreviewTransform extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_data_frame/transforms/_preview";
    }

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function setBody($body): PreviewTransform
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
