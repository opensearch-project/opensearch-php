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

class PatchActionGroups extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $action_group;

    public function getParamWhitelist(): array
    {
        return [];
    }

    public function getURI(): string
    {
        return '/_plugins/_security/api/actiongroups' . ($this->action_group ? "/{$this->action_group}" : '');
    }

    public function getMethod(): string
    {
        return 'PATCH';
    }

    /**
     * @param string|null $action_group
     * @return PatchActionGroups
     */
    public function setActionGroup(?string $action_group): PatchActionGroups
    {
        $this->action_group = $action_group;
        return $this;
    }
}
