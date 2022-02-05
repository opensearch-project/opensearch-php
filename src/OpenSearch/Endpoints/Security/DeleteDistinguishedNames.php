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

class DeleteDistinguishedNames extends AbstractEndpoint
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
        if (!isset($this->cluster_name)) {
            throw new RuntimeException('Missing parameter for the endpoint security.delete_distinguished_names');
        }

        return "/_plugins/_security/api/nodesdn/$this->cluster_name";
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    /**
     * @param string|null $cluster_name
     * @return DeleteDistinguishedNames
     */
    public function setClusterName(?string $cluster_name): DeleteDistinguishedNames
    {
        $this->cluster_name = $cluster_name;
        return $this;
    }
}
