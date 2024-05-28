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

namespace OpenSearch\Endpoints\Security;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class UpdateDistinguishedName extends AbstractEndpoint
{
    protected $cluster_name;

    public function getURI(): string
    {
        if (isset($this->cluster_name) !== true) {
            throw new RuntimeException(
                'cluster_name is required for update_distinguished_name'
            );
        }
        $cluster_name = $this->cluster_name;

        return "/_plugins/_security/api/nodesdn/$cluster_name";
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
        return 'PUT';
    }

    public function setBody($body): UpdateDistinguishedName
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }

    public function setClusterName($cluster_name): UpdateDistinguishedName
    {
        if (isset($cluster_name) !== true) {
            return $this;
        }
        $this->cluster_name = $cluster_name;

        return $this;
    }
}
