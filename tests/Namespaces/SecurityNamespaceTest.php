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

namespace OpenSearch\Tests\Namespaces;

use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Namespaces\SecurityNamespace;
use OpenSearch\Transport;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use UnexpectedValueException;

class SecurityNamespaceTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Transport|MockObject
     */
    private $transport;

    protected function setUp(): void
    {
        $this->transport = $this->createMock(Transport::class);
        $this->client = ClientBuilder::create()
            ->setTransport($this->transport)
            ->setSSLVerification(false)
            ->build();
    }

    /**
     * @return array<mixed>
     */
    public function methodProvider(): array
    {
        return array_map(function (ReflectionMethod $method) {
            return [$method->name];
        }, array_filter(
            (new ReflectionClass(SecurityNamespace::class))->getMethods(),
            function (ReflectionMethod $method) {
                return $method->class === SecurityNamespace::class;
            }
        ));
    }

    /**
     * @dataProvider methodProvider
     */
    public function testWithInvalidParams(string $methodName): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('"invalid" is not a valid parameter.');

        $this->client->security()->$methodName([
            'invalid' => 'abc',
        ]);
    }

    public function testChangePassword(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/account', [], [
                'password' => 'abc',
                'current_password' => 'abc',
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->changePassword([
            'password' => 'abc',
            'current_password' => 'abc'
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testCreateActionGroup(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/actiongroups/my_test_action_group', [], [
                'allowed_actions' => ['indices:data/read*']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->createActionGroup([
            'action_group' => 'my_test_action_group',
            'allowed_actions' => ['indices:data/read*']
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testCreateActionGroupThrowsWithoutActionGroup(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('action_group is required for create_action_group');

        $this->client->security()->createActionGroup([
            'allowed_actions' => ['indices:data/read*']
        ]);
    }

    public function testCreateRole(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/roles/my_test_role', [], [
                'cluster_permissions' => [
                    'cluster_composite_ops',
                    'indices_monitor'
                ],
                'index_permissions' => [
                    [
                        'index_patterns' => [
                            'movies*'
                        ],
                        'dls' => '',
                        'fls' => [],
                        'masked_fields' => [],
                        'allowed_actions' => [
                            'read'
                        ]
                    ]
                ],
                'tenant_permissions' => [
                    [
                        'tenant_patterns' => [
                            'human_resources'
                        ],
                        'allowed_actions' => [
                            'kibana_all_read'
                        ]
                    ]
                ]
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->createRole([
            'role' => 'my_test_role',
            'cluster_permissions' => [
                'cluster_composite_ops',
                'indices_monitor'
            ],
            'index_permissions' => [
                [
                    'index_patterns' => [
                        'movies*'
                    ],
                    'dls' => '',
                    'fls' => [],
                    'masked_fields' => [],
                    'allowed_actions' => [
                        'read'
                    ]
                ]
            ],
            'tenant_permissions' => [
                [
                    'tenant_patterns' => [
                        'human_resources'
                    ],
                    'allowed_actions' => [
                        'kibana_all_read'
                    ]
                ]
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testCreateRoleThrowsWithoutRole(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('role is required for create_role');

        $this->client->security()->createRole([
            'cluster_permissions' => [],
            'index_permissions' => [
                [
                    'index_patterns' => [
                        'test_index*'
                    ],
                ]
            ],
            'tenant_permissions' => [],
        ]);
    }

    public function testCreateRoleMapping(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/rolesmapping/my_test_role_mapping', [], [
                'backend_roles' => ['starfleet', 'captains', 'defectors', 'cn=ldaprole,ou=groups,dc=example,dc=com'],
                'hosts' => ['*.starfleetintranet.com'],
                'users' => ['worf'],
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->createRoleMapping([
            'role' => 'my_test_role_mapping',
            'backend_roles' => ['starfleet', 'captains', 'defectors', 'cn=ldaprole,ou=groups,dc=example,dc=com'],
            'hosts' => ['*.starfleetintranet.com'],
            'users' => ['worf'],
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testCreateRoleMappingThrowsWithoutRole(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('role is required for create_role_mapping');

        $this->client->security()->createRoleMapping([
            'backend_roles' => ['starfleet', 'captains', 'defectors', 'cn=ldaprole,ou=groups,dc=example,dc=com'],
            'hosts' => ['*.starfleetintranet.com'],
            'users' => ['worf'],
        ]);
    }

    public function testCreateTenant(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/tenants/my_test_tenant', [], [
                'description' => 'My test tenant'
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->createTenant([
            'tenant' => 'my_test_tenant',
            'description' => 'My test tenant'
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testCreateTenantThrowsWithoutRole(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('tenant is required for create_tenant');

        $this->client->security()->createTenant([
            'description' => 'My test tenant'
        ]);
    }

    public function testCreateUser(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/internalusers/my_test_username', [], [
                'password' => 'kirkpass',
                'opendistro_security_roles' => ['maintenance_staff', 'weapons'],
                'backend_roles' => ['captains', 'starfleet'],
                'attributes' => [
                    'attribute1' => 'value1',
                    'attribute2' => 'value2'
                ]
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->createUser([
            'username' => 'my_test_username',
            'password' => 'kirkpass',
            'opendistro_security_roles' => ['maintenance_staff', 'weapons'],
            'backend_roles' => ['captains', 'starfleet'],
            'attributes' => [
                'attribute1' => 'value1',
                'attribute2' => 'value2'
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testCreateUserThrowsWithoutUsername(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('username is required for create_user');

        $this->client->security()->createUser([
            'password' => 'kirkpass',
            'opendistro_security_roles' => ['maintenance_staff', 'weapons'],
            'backend_roles' => ['captains', 'starfleet'],
            'attributes' => [
                'attribute1' => 'value1',
                'attribute2' => 'value2'
            ]
        ]);
    }

    public function testDeleteActionGroup(): void
    {
        $this->transport->method('performRequest')
            ->with('DELETE', '/_plugins/_security/api/actiongroups/my_test_action_group', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->deleteActionGroup([
            'action_group' => 'my_test_action_group',
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testDeleteActionGroupThrowsWithoutActionGroupName(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('action_group is required for delete_action_group');

        $this->client->security()->deleteActionGroup();
    }

    public function testDeleteDistinguishedNames(): void
    {
        $this->transport->method('performRequest')
            ->with('DELETE', '/_plugins/_security/api/nodesdn/my_test_cluster', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->deleteDistinguishedNames([
            'cluster_name' => 'my_test_cluster',
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testDeleteDistinguishedNamesThrowsWithoutRoleName(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('cluster_name is required for delete_distinguished_name');

        $this->client->security()->deleteDistinguishedNames();
    }

    public function testDeleteRole(): void
    {
        $this->transport->method('performRequest')
            ->with('DELETE', '/_plugins/_security/api/roles/my_test_role', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->deleteRole([
            'role' => 'my_test_role',
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testDeleteRoleThrowsWithoutRole(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('role is required for delete_role');

        $this->client->security()->deleteRole();
    }

    public function testDeleteRoleMapping(): void
    {
        $this->transport->method('performRequest')
            ->with('DELETE', '/_plugins/_security/api/rolesmapping/my_test_role_mapping', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->deleteRoleMapping([
            'role' => 'my_test_role_mapping',
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testDeleteRoleMappingThrowsWithoutRoleMappingName(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('role is required for delete_role_mapping');

        $this->client->security()->deleteRoleMapping();
    }

    public function testDeleteTenant(): void
    {
        $this->transport->method('performRequest')
            ->with('DELETE', '/_plugins/_security/api/tenants/my_test_tenant', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->deleteTenant([
            'tenant' => 'my_test_tenant',
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testDeleteTenantThrowsWithoutTenantName(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('tenant is required for delete_tenant');

        $this->client->security()->deleteTenant();
    }

    public function testDeleteUser(): void
    {
        $this->transport->method('performRequest')
            ->with('DELETE', '/_plugins/_security/api/internalusers/my_test_user', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->client->security()->deleteUser([
            'username' => 'my_test_user',
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testDeleteUserThrowsWithoutUsername(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('username is required for delete_user');

        $this->client->security()->deleteUser();
    }

    public function testFlushCache(): void
    {
        $this->transport->method('performRequest')
            ->with('DELETE', '/_plugins/_security/api/cache', [], null);

        $this->client->security()->flushCache();
    }

    public function testGetAccount(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/account', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getAccount();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetActionGroups(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/actiongroups', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getActionGroups([
            'action_group' => 'my_test_action_group',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetActionGroupsWithoutActionGroupName(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/actiongroups', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getActionGroups();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetCertificates(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/ssl/certs', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getCertificates();

        static::assertSame([
            'resource' => ['test_resource'],
        ], $response);
    }

    public function testGetConfig(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/securityconfig', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getConfig();

        static::assertSame([
            'resource' => ['test_resource'],
        ], $response);
    }

    public function testGetDistinguishedNames(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/nodesdn/my_test_cluster', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getDistinguishedNames([
            'cluster_name' => 'my_test_cluster',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetDistinguishedNamesWithoutClusterName(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/nodesdn', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getDistinguishedNames();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRoleMappings(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/rolesmapping/my_test_role', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getRoleMappings([
            'role' => 'my_test_role',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRoleMappingsWithoutRoleName(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/rolesmapping', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getRoleMappings();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRoles(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/roles/my_test_role', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getRoles([
            'role' => 'my_test_role',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRolesWithoutActionGroupName(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/roles', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getRoles();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetTenants(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/tenants/my_test_tenant', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getTenants([
            'tenant' => 'my_test_tenant',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetTenantsWithoutTenantName(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/tenants', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getTenants();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetUsers(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/internalusers/my_test_user', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getUsers([
            'username' => 'my_test_user',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetUsersWithoutUsername(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/api/internalusers', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->client->security()->getUsers();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testHealth(): void
    {
        $this->transport->method('performRequest')
            ->with('GET', '/_plugins/_security/health', [], null);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->health();

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchActionGroups(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/actiongroups/my_test_action_group', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchActionGroups([
            'action_group' => 'my_test_action_group',
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchActionGroupsWithoutActionGroupName(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/actiongroups', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchActionGroups([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchConfig(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/securityconfig', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchConfig([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchRoleMappings(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/rolesmapping/my_test_role_mapping', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchRoleMappings([
            'role' => 'my_test_role_mapping',
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchRoleMappingsWithoutRoleMappingName(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/rolesmapping', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchRoleMappings([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchRoles(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/roles/my_test_role', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchRoles([
            'role' => 'my_test_role',
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchRolesWithoutRoleName(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/roles', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchRoles([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchTenants(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/tenants/my_test_tenant', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchTenants([
            'tenant' => 'my_test_tenant',
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchTenantsWithoutRoleName(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/tenants', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchTenants([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchUsers(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/internalusers/my_test_user', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchUsers([
            'username' => 'my_test_user',
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchUsersWithoutUsername(): void
    {
        $this->transport->method('performRequest')
            ->with('PATCH', '/_plugins/_security/api/internalusers', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->patchUsers([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testUpdateConfig(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/securityconfig/config', [], [
                'dynamic' => [
                    'filtered_alias_mode' => 'warn',
                ]
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->updateConfig([
            'dynamic' => [
                'filtered_alias_mode' => 'warn',
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testUpdateDistinguishedNames(): void
    {
        $this->transport->method('performRequest')
            ->with('PUT', '/_plugins/_security/api/securityconfig/config', [], [
                'dynamic' => [
                    'filtered_alias_mode' => 'warn',
                ]
            ]);
        $this->transport->method('resultOrFuture')
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->client->security()->updateConfig([
            'dynamic' => [
                'filtered_alias_mode' => 'warn',
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }
}
