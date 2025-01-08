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

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\EndpointFactory;
use OpenSearch\Namespaces\SecurityNamespace;
use OpenSearch\TransportInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use UnexpectedValueException;

class SecurityNamespaceTest extends TestCase
{
    private SecurityNamespace $securityNamespace;

    private TransportInterface&MockObject $transport;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transport = $this->createMock(TransportInterface::class);
        $this->securityNamespace = new SecurityNamespace($this->transport, new EndpointFactory());
    }

    /**
     * @return array<mixed>
     */
    public function methodProvider(): array
    {
        return array_map(
            function (ReflectionMethod $method) {
                return [$method->name];
            },
            array_filter(
                (new ReflectionClass(SecurityNamespace::class))->getMethods(),
                function (ReflectionMethod $method) {
                    return $method->class === SecurityNamespace::class;
                }
            )
        );
    }

    /**
     * @dataProvider methodProvider
     */
    public function testWithInvalidParams(string $methodName): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('"invalid" is not a valid parameter.');

        $this->securityNamespace->$methodName([
            'invalid' => 'abc',
        ]);
    }

    public function testChangePassword(): void
    {
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/account', [], [
                'password' => 'abc',
                'current_password' => 'abc',
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->changePassword([
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
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/actiongroups/my_test_action_group', [], [
                'allowed_actions' => ['indices:data/read*']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->createActionGroup([
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

        $this->securityNamespace->createActionGroup([
            'allowed_actions' => ['indices:data/read*']
        ]);
    }

    public function testCreateRole(): void
    {
        $this->transport->method('sendRequest')
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
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->createRole([
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

        $this->securityNamespace->createRole([
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
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/rolesmapping/my_test_role_mapping', [], [
                'backend_roles' => ['starfleet', 'captains', 'defectors', 'cn=ldaprole,ou=groups,dc=example,dc=com'],
                'hosts' => ['*.starfleetintranet.com'],
                'users' => ['worf'],
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->createRoleMapping([
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

        $this->securityNamespace->createRoleMapping([
            'backend_roles' => ['starfleet', 'captains', 'defectors', 'cn=ldaprole,ou=groups,dc=example,dc=com'],
            'hosts' => ['*.starfleetintranet.com'],
            'users' => ['worf'],
        ]);
    }

    public function testCreateTenant(): void
    {
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/tenants/my_test_tenant', [], [
                'description' => 'My test tenant'
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->createTenant([
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

        $this->securityNamespace->createTenant([
            'description' => 'My test tenant'
        ]);
    }

    public function testCreateUser(): void
    {
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/internalusers/my_test_username', [], [
                'password' => 'kirkpass',
                'opendistro_security_roles' => ['maintenance_staff', 'weapons'],
                'backend_roles' => ['captains', 'starfleet'],
                'attributes' => [
                    'attribute1' => 'value1',
                    'attribute2' => 'value2'
                ]
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->createUser([
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

        $this->securityNamespace->createUser([
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
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/actiongroups/my_test_action_group', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->deleteActionGroup([
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

        $this->securityNamespace->deleteActionGroup();
    }

    public function testDeleteDistinguishedNames(): void
    {
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/nodesdn/my_test_cluster', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->deleteDistinguishedNames([
            'cluster_name' => 'my_test_cluster',
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $result);
    }

    public function testDeleteDistinguishedName(): void
    {
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/nodesdn/my_test_cluster', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->deleteDistinguishedName([
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

        $this->securityNamespace->deleteDistinguishedNames();
    }

    public function testDeleteRole(): void
    {
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/roles/my_test_role', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->deleteRole([
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

        $this->securityNamespace->deleteRole();
    }

    public function testDeleteRoleMapping(): void
    {
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/rolesmapping/my_test_role_mapping', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->deleteRoleMapping([
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

        $this->securityNamespace->deleteRoleMapping();
    }

    public function testDeleteTenant(): void
    {
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/tenants/my_test_tenant', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->deleteTenant([
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

        $this->securityNamespace->deleteTenant();
    }

    public function testDeleteUser(): void
    {
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/internalusers/my_test_user', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $result = $this->securityNamespace->deleteUser([
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

        $this->securityNamespace->deleteUser();
    }

    public function testFlushCache(): void
    {
        $this->transport->method('sendRequest')
            ->with('DELETE', '/_plugins/_security/api/cache', [], null);

        $this->securityNamespace->flushCache();
    }

    public function testGetAccount(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/account', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getAccount();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetAccountDetails(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/account', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getAccountDetails();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetActionGroups(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/actiongroups/my_test_action_group', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getActionGroups([
            'action_group' => 'my_test_action_group',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetActionGroupsWithoutActionGroupName(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/actiongroups', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getActionGroups();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetActionGroup(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/actiongroups/my_test_action_group', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getActionGroup([
            'action_group' => 'my_test_action_group',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetCertificates(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/ssl/certs', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getCertificates();

        static::assertSame([
            'resource' => ['test_resource'],
        ], $response);
    }

    public function testGetConfig(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/securityconfig', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getConfig();

        static::assertSame([
            'resource' => ['test_resource'],
        ], $response);
    }

    public function testGetConfiguration(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/securityconfig', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getConfiguration();

        static::assertSame([
            'resource' => ['test_resource'],
        ], $response);
    }

    public function testGetDistinguishedNames(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/nodesdn/my_test_cluster', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getDistinguishedNames([
            'cluster_name' => 'my_test_cluster',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetDistinguishedNamesWithoutClusterName(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/nodesdn', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getDistinguishedNames();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetDistinguishedName(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/nodesdn/my_test_cluster', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getDistinguishedName([
            'cluster_name' => 'my_test_cluster',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRoleMappings(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/rolesmapping/my_test_role', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getRoleMappings([
            'role' => 'my_test_role',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRoleMappingsWithoutRoleName(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/rolesmapping', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getRoleMappings();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRoleMapping(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/rolesmapping/my_test_role', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getRoleMapping([
            'role' => 'my_test_role',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRoles(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/roles/my_test_role', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getRoles([
            'role' => 'my_test_role',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRolesWithoutActionGroupName(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/roles', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getRoles();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetRole(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/roles/my_test_role', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getRole([
            'role' => 'my_test_role',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetTenants(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/tenants/my_test_tenant', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getTenants([
            'tenant' => 'my_test_tenant',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetTenantsWithoutTenantName(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/tenants', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getTenants();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetTenant(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/tenants/my_test_tenant', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getTenant([
            'tenant' => 'my_test_tenant',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetUsers(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/internalusers/my_test_user', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getUsers([
            'username' => 'my_test_user',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetUsersWithoutUsername(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/internalusers', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getUsers();

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testGetUser(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/api/internalusers/my_test_user', [], null)
            ->willReturn([
                'resource' => ['test_resource'],
            ]);

        $response = $this->securityNamespace->getUser([
            'username' => 'my_test_user',
        ]);

        static::assertSame([
            'resource' => ['test_resource']
        ], $response);
    }

    public function testHealth(): void
    {
        $this->transport->method('sendRequest')
            ->with('GET', '/_plugins/_security/health', [], null)
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->health();

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchActionGroups(): void
    {
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/actiongroups/my_test_action_group', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchActionGroups([
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/actiongroups', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchActionGroups([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchActionGroup(): void
    {
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/actiongroups/my_test_action_group', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchActionGroup([
            'action_group' => 'my_test_action_group',
            'body' => [
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/securityconfig', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchConfig([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchConfiguration(): void
    {
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/securityconfig', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchConfiguration([
            'body' => [
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/rolesmapping/my_test_role_mapping', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchRoleMappings([
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/rolesmapping', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchRoleMappings([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchRoleMapping(): void
    {
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/rolesmapping/my_test_role_mapping', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchRoleMapping([
            'role' => 'my_test_role_mapping',
            'body' => [
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/roles/my_test_role', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchRoles([
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/roles', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchRoles([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchRole(): void
    {
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/roles/my_test_role', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchRole([
            'role' => 'my_test_role',
            'body' => [
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/tenants/my_test_tenant', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchTenants([
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/tenants', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchTenants([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchTenant(): void
    {
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/tenants/my_test_tenant', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchTenant([
            'tenant' => 'my_test_tenant',
            'body' => [
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/internalusers/my_test_user', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchUsers([
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
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/internalusers', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchUsers([
            'ops' => [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testPatchUser(): void
    {
        $this->transport->method('sendRequest')
            ->with('PATCH', '/_plugins/_security/api/internalusers/my_test_user', [], [
                ['op' => 'remove', 'path' => '/index_permissions/0/dls']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->patchUser([
            'username' => 'my_test_user',
            'body' => [
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
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/securityconfig/config', [], [
                'dynamic' => [
                    'filtered_alias_mode' => 'warn',
                ]
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->updateConfig([
            'dynamic' => [
                'filtered_alias_mode' => 'warn',
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testUpdateConfiguration(): void
    {
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/securityconfig/config', [], [
                'dynamic' => [
                    'filtered_alias_mode' => 'warn',
                ]
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->updateConfiguration([
            'body' => [
                'dynamic' => ['filtered_alias_mode' => 'warn',]
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testUpdateDistinguishedNames(): void
    {
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/nodesdn/my_test_cluster', [], [
                'nodes_dn' => ['CN=cluster3.example.com']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->updateDistinguishedNames([
            'cluster_name' => 'my_test_cluster',
            'nodes_dn' => ['CN=cluster3.example.com']
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }

    public function testUpdateDistinguishedName(): void
    {
        $this->transport->method('sendRequest')
            ->with('PUT', '/_plugins/_security/api/nodesdn/my_test_cluster', [], [
                'nodes_dn' => ['CN=cluster3.example.com']
            ])
            ->willReturn([
                'status' => 'OK',
                'message' => 'Stubbed response'
            ]);

        $response = $this->securityNamespace->updateDistinguishedName([
            'cluster_name' => 'my_test_cluster',
            'body' => [
                'nodes_dn' => ['CN=cluster3.example.com']
            ]
        ]);

        static::assertSame([
            'status' => 'OK',
            'message' => 'Stubbed response'
        ], $response);
    }
}
