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

use OpenSearch\Endpoints\AbstractEndpoint;

class GetDistinguishedNames extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $cluster_name;

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getURI(): string
    {
        return '/_plugins/_security/api/nodesdn' . ($this->cluster_name ? "/{$this->cluster_name}" : '');
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @param string|null $cluster_name
     * @return GetDistinguishedNames
     */
    public function setClusterName(?string $cluster_name): GetDistinguishedNames
    {
        $this->cluster_name = $cluster_name;
        return $this;
    }
}
