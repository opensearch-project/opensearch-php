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

namespace OpenSearch\Endpoints;

use OpenSearch\Common\Exceptions\InvalidArgumentException;
use OpenSearch\Endpoints\AbstractEndpoint;
use OpenSearch\Serializers\SerializerInterface;
use Traversable;

/**
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class BulkStream extends AbstractEndpoint
{
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getURI(): string
    {
        $index = $this->index ?? null;
        if (isset($index)) {
            return "/$index/_bulk/stream";
        }
        return "/_bulk/stream";
    }

    public function getParamWhitelist(): array
    {
        return [
            '_source',
            '_source_excludes',
            '_source_includes',
            'batch_interval',
            'batch_size',
            'pipeline',
            'refresh',
            'require_alias',
            'routing',
            'timeout',
            'wait_for_active_shards',
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function setBody($body): static
    {
        if (isset($body) !== true) {
            return $this;
        }
        if (is_array($body) === true || $body instanceof Traversable) {
            foreach ($body as $item) {
                $this->body .= $this->serializer->serialize($item) . "\n";
            }
        } elseif (is_string($body)) {
            $this->body = $body;
            if (substr($body, -1) != "\n") {
                $this->body .= "\n";
            }
        } else {
            throw new InvalidArgumentException("Body must be an array, traversable object or string");
        }
        return $this;
    }

}
