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

interface SerializerInterface
{
    /**
     * Serialize a complex data-structure into a json encoded string
     *
     * @param  mixed $data The data to encode
     * @return string
     */
    public function serialize($data): string;

    /**
     * Deserialize json encoded string into an associative array
     *
     * @param  string $data    JSON encoded string
     * @param  array  $headers Response Headers
     * @return string|array
     */
    public function deserialize(?string $data, array $headers);
}
