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

namespace OpenSearch\Serializers;

use OpenSearch\Common\Exceptions\RuntimeException;

if (!defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
    //PHP < 7.2 Define it as 0 so it does nothing
    define('JSON_INVALID_UTF8_SUBSTITUTE', 0);
}

class EverythingToJSONSerializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function serialize($data): string
    {
        $data = json_encode($data, JSON_PRESERVE_ZERO_FRACTION + JSON_INVALID_UTF8_SUBSTITUTE);
        if ($data === false) {
            throw new RuntimeException("Failed to JSON encode: ".json_last_error());
        }
        if ($data === '[]') {
            return '{}';
        } else {
            return $data;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize(?string $data, array $headers)
    {
        return json_decode($data, true);
    }
}
