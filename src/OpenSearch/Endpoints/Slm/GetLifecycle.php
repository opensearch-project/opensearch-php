<?php
/**
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1 
 * 
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */
declare(strict_types = 1);

namespace OpenSearch\Endpoints\Slm;

use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class GetLifecycle
 * Elasticsearch API name slm.get_lifecycle
 *
 */
class GetLifecycle extends AbstractEndpoint
{
    protected $policy_id;

    public function getURI(): string
    {
        $policy_id = $this->policy_id ?? null;

        if (isset($policy_id)) {
            return "/_slm/policy/$policy_id";
        }
        return "/_slm/policy";
    }

    public function getParamWhitelist(): array
    {
        return [
            
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setPolicyId($policy_id): GetLifecycle
    {
        if (isset($policy_id) !== true) {
            return $this;
        }
        if (is_array($policy_id) === true) {
            $policy_id = implode(",", $policy_id);
        }
        $this->policy_id = $policy_id;

        return $this;
    }
}
