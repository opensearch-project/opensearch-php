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

namespace OpenSearch\Endpoints\Security;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

/**
 * Class DeleteUser
 * Elasticsearch API name security.delete_user
 *
 */
class DeleteUser extends AbstractEndpoint
{
    protected $username;

    public function getURI(): string
    {
        $username = $this->username ?? null;

        if (isset($username)) {
            return "/_security/user/$username";
        }
        throw new RuntimeException('Missing parameter for the endpoint security.delete_user');
    }

    public function getParamWhitelist(): array
    {
        return [
            'refresh'
        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function setUsername($username): DeleteUser
    {
        if (isset($username) !== true) {
            return $this;
        }
        $this->username = $username;

        return $this;
    }
}
