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

class CreateActionGroup extends AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $action_group;

    public function getParamWhitelist(): array
    {
        return [
            'allowed_actions'
        ];
    }

    public function getURI(): string
    {
        if (!isset($this->action_group)) {
            throw new RuntimeException('Missing parameter for the endpoint security.create_action_group');
        }

        return "/_plugins/_security/api/actiongroups/$this->action_group";
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * @param string|null $action_group
     * @return CreateActionGroup
     */
    public function setActionGroup(?string $action_group): CreateActionGroup
    {
        $this->action_group = $action_group;

        return $this;
    }
}
