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

class DeleteActionGroup extends AbstractEndpoint
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
        if (!isset($this->action_group)) {
            throw new RuntimeException('Missing parameter for the endpoint security.delete_action_group');
        }

        return "/_plugins/_security/api/actiongroups/$this->action_group";
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    /**
     * @param string|null $action_group
     * @return DeleteActionGroup
     */
    public function setActionGroup(?string $action_group): DeleteActionGroup
    {
        $this->action_group = $action_group;
        return $this;
    }
}
