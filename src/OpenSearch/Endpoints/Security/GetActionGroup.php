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

class GetActionGroup extends AbstractEndpoint
{
    protected $action_group;

    public function getURI(): string
    {
        if (isset($this->action_group) !== true) {
            throw new RuntimeException(
                'action_group is required for get_action_group'
            );
        }
        $action_group = $this->action_group;

        return "/_plugins/_security/api/actiongroups/$action_group";
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
        return 'GET';
    }

    public function setActionGroup($action_group): GetActionGroup
    {
        if (isset($action_group) !== true) {
            return $this;
        }
        $this->action_group = $action_group;

        return $this;
    }
}
