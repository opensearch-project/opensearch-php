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

class UpdateDistinguishedNames extends AbstractEndpoint
{
    /**
     * @var string
     */
    protected $cluster_name;

    public function getParamWhitelist(): array
    {
        return [
            'nodes_dn',
        ];
    }

    public function getURI(): string
    {
        if (!isset($this->cluster_name)) {
            throw new RuntimeException('Missing parameter for the endpoint security.update_distinguished_names');
        }

        return "/_plugins/_security/api/nodesdn/{$this->cluster_name}";
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * @param string|null $cluster_name
     * @return UpdateDistinguishedNames
     */
    public function setClusterName(?string $cluster_name): UpdateDistinguishedNames
    {
        $this->cluster_name = $cluster_name;
        return $this;
    }
}
