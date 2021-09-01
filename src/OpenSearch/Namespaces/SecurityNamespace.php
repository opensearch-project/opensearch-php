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

namespace OpenSearch\Namespaces;

use OpenSearch\Namespaces\AbstractNamespace;

/**
 * Class SecurityNamespace
 *
 */
class SecurityNamespace extends AbstractNamespace
{
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function authenticate(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\Authenticate');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['username'] = (string) The username of the user to change the password for
     * $params['refresh']  = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     * $params['body']     = (array) the new password for the user (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function changePassword(array $params = [])
    {
        $username = $this->extractArgument($params, 'username');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\ChangePassword');
        $endpoint->setParams($params);
        $endpoint->setUsername($username);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['ids'] = (list) A comma-separated list of IDs of API keys to clear from the cache
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function clearApiKeyCache(array $params = [])
    {
        $ids = $this->extractArgument($params, 'ids');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\ClearApiKeyCache');
        $endpoint->setParams($params);
        $endpoint->setIds($ids);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['application'] = (list) A comma-separated list of application names
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function clearCachedPrivileges(array $params = [])
    {
        $application = $this->extractArgument($params, 'application');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\ClearCachedPrivileges');
        $endpoint->setParams($params);
        $endpoint->setApplication($application);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['realms']    = (list) Comma-separated list of realms to clear
     * $params['usernames'] = (list) Comma-separated list of usernames to clear from the cache
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function clearCachedRealms(array $params = [])
    {
        $realms = $this->extractArgument($params, 'realms');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\ClearCachedRealms');
        $endpoint->setParams($params);
        $endpoint->setRealms($realms);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['name'] = (list) Role name
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function clearCachedRoles(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\ClearCachedRoles');
        $endpoint->setParams($params);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['refresh'] = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     * $params['body']    = (array) The api key request to create an API key (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function createApiKey(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\CreateApiKey');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['application'] = (string) Application name
     * $params['name']        = (string) Privilege name
     * $params['refresh']     = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deletePrivileges(array $params = [])
    {
        $application = $this->extractArgument($params, 'application');
        $name = $this->extractArgument($params, 'name');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\DeletePrivileges');
        $endpoint->setParams($params);
        $endpoint->setApplication($application);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['name']    = (string) Role name
     * $params['refresh'] = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteRole(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\DeleteRole');
        $endpoint->setParams($params);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['name']    = (string) Role-mapping name
     * $params['refresh'] = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteRoleMapping(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\DeleteRoleMapping');
        $endpoint->setParams($params);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['username'] = (string) username
     * $params['refresh']  = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteUser(array $params = [])
    {
        $username = $this->extractArgument($params, 'username');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\DeleteUser');
        $endpoint->setParams($params);
        $endpoint->setUsername($username);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['username'] = (string) The username of the user to disable
     * $params['refresh']  = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function disableUser(array $params = [])
    {
        $username = $this->extractArgument($params, 'username');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\DisableUser');
        $endpoint->setParams($params);
        $endpoint->setUsername($username);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['username'] = (string) The username of the user to enable
     * $params['refresh']  = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function enableUser(array $params = [])
    {
        $username = $this->extractArgument($params, 'username');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\EnableUser');
        $endpoint->setParams($params);
        $endpoint->setUsername($username);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['id']         = (string) API key id of the API key to be retrieved
     * $params['name']       = (string) API key name of the API key to be retrieved
     * $params['username']   = (string) user name of the user who created this API key to be retrieved
     * $params['realm_name'] = (string) realm name of the user who created this API key to be retrieved
     * $params['owner']      = (boolean) flag to query API keys owned by the currently authenticated user (Default = false)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getApiKey(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetApiKey');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getBuiltinPrivileges(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetBuiltinPrivileges');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['application'] = (string) Application name
     * $params['name']        = (string) Privilege name
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getPrivileges(array $params = [])
    {
        $application = $this->extractArgument($params, 'application');
        $name = $this->extractArgument($params, 'name');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetPrivileges');
        $endpoint->setParams($params);
        $endpoint->setApplication($application);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['name'] = (list) A comma-separated list of role names
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getRole(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetRole');
        $endpoint->setParams($params);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['name'] = (list) A comma-separated list of role-mapping names
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getRoleMapping(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetRoleMapping');
        $endpoint->setParams($params);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['body'] = (array) The token request to get (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getToken(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetToken');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['username'] = (list) A comma-separated list of usernames
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getUser(array $params = [])
    {
        $username = $this->extractArgument($params, 'username');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetUser');
        $endpoint->setParams($params);
        $endpoint->setUsername($username);

        return $this->performRequest($endpoint);
    }
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getUserPrivileges(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GetUserPrivileges');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['refresh'] = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     * $params['body']    = (array) The api key request to create an API key (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function grantApiKey(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\GrantApiKey');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['user'] = (string) Username
     * $params['body'] = (array) The privileges to test (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function hasPrivileges(array $params = [])
    {
        $user = $this->extractArgument($params, 'user');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\HasPrivileges');
        $endpoint->setParams($params);
        $endpoint->setUser($user);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    public function invalidateApiKey(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\InvalidateApiKey');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['body'] = (array) The token to invalidate (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function invalidateToken(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\InvalidateToken');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['refresh'] = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     * $params['body']    = (array) The privilege(s) to add (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putPrivileges(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\PutPrivileges');
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['name']    = (string) Role name
     * $params['refresh'] = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     * $params['body']    = (array) The role to add (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putRole(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\PutRole');
        $endpoint->setParams($params);
        $endpoint->setName($name);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['name']    = (string) Role-mapping name
     * $params['refresh'] = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     * $params['body']    = (array) The role mapping to add (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putRoleMapping(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\PutRoleMapping');
        $endpoint->setParams($params);
        $endpoint->setName($name);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
    /**
     * $params['username'] = (string) The username of the User
     * $params['refresh']  = (enum) If `true` (the default) then refresh the affected shards to make this operation visible to search, if `wait_for` then wait for a refresh to make this operation visible to search, if `false` then do nothing with refreshes. (Options = true,false,wait_for)
     * $params['body']     = (array) The user to add (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putUser(array $params = [])
    {
        $username = $this->extractArgument($params, 'username');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Security\PutUser');
        $endpoint->setParams($params);
        $endpoint->setUsername($username);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
}
