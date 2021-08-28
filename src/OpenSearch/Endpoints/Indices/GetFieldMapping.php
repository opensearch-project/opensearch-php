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
 * Class GetFieldMapping
 * Elasticsearch API name indices.get_field_mapping
 *
 */
class GetFieldMapping extends AbstractEndpoint
{
    protected $fields;

    public function getURI(): string
    {
        if (isset($this->fields) !== true) {
            throw new RuntimeException(
                'fields is required for get_field_mapping'
            );
        }
        $fields = $this->fields;
        $index = $this->index ?? null;
        $type = $this->type ?? null;
        if (isset($type)) {
            @trigger_error('Specifying types in urls has been deprecated', E_USER_DEPRECATED);
        }

        if (isset($index) && isset($type)) {
            return "/$index/_mapping/$type/field/$fields";
        }
        if (isset($index)) {
            return "/$index/_mapping/field/$fields";
        }
        if (isset($type)) {
            return "/_mapping/$type/field/$fields";
        }
        return "/_mapping/field/$fields";
    }

    public function getParamWhitelist(): array
    {
        return [
            'include_type_name',
            'include_defaults',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'local'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setFields($fields): GetFieldMapping
    {
        if (isset($fields) !== true) {
            return $this;
        }
        if (is_array($fields) === true) {
            $fields = implode(",", $fields);
        }
        $this->fields = $fields;

        return $this;
    }
}
