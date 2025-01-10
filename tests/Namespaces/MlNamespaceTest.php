<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Namespaces;

use OpenSearch\EndpointFactory;
use OpenSearch\EndpointFactoryInterface;
use OpenSearch\Endpoints\Ml\CreateConnector;
use OpenSearch\Endpoints\Ml\DeleteConnector;
use OpenSearch\Endpoints\Ml\DeleteModel;
use OpenSearch\Endpoints\Ml\DeleteModelGroup;
use OpenSearch\Endpoints\Ml\DeployModel;
use OpenSearch\Endpoints\Ml\GetConnector;
use OpenSearch\Endpoints\Ml\GetConnectors;
use OpenSearch\Endpoints\Ml\GetModel;
use OpenSearch\Endpoints\Ml\GetModelGroups;
use OpenSearch\Endpoints\Ml\GetTask;
use OpenSearch\Endpoints\Ml\Predict;
use OpenSearch\Endpoints\Ml\RegisterModel;
use OpenSearch\Endpoints\Ml\RegisterModelGroup;
use OpenSearch\Endpoints\Ml\SearchModels;
use OpenSearch\Endpoints\Ml\UndeployModel;
use OpenSearch\Endpoints\Ml\UpdateModelGroup;
use OpenSearch\Namespaces\MlNamespace;
use OpenSearch\Namespaces\SecurityNamespace;
use OpenSearch\Request;
use OpenSearch\Response;
use OpenSearch\TransportInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Copyright OpenSearch Contributors
 *  SPDX-License-Identifier: Apache-2.0
 *
 *  The OpenSearch Contributors require contributions made to
 *  this file be licensed under the Apache-2.0 license or a
 *  compatible open source license.
 */
class MlNamespaceTest extends TestCase
{
    private MlNamespace $mlNamespace;

    private TransportInterface&MockObject $transport;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transport = $this->createMock(TransportInterface::class);
        $this->mlNamespace = new MlNamespace($this->transport, new EndpointFactory());
    }

    public function testCreatingConnector(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('POST', '/_plugins/_ml/connectors/_create', [], [
                    'foo' => 'bar',
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->createConnector([
            'body' => [
                'foo' => 'bar',
            ],
        ]);
    }

    public function testGetConnector(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('GET', '/_plugins/_ml/connectors/foobar'))
            ->willReturn(new Response());

        $this->mlNamespace->getConnector([
            'id' => 'foobar',
            'connector_id' => 'foobar'
        ]);
    }

    public function testGetConnectors(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('POST', '/_plugins/_ml/connectors/_search', [], [
                    'query' => [
                        'match_all' => new \StdClass(),
                    ],
                    'size' => 1000,
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->getConnectors([
            'body' => [
                'query' => [
                    'match_all' => new \StdClass(),
                ],
                'size' => 1000,
            ],
        ]);
    }

    public function testDeleteConnector(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('DELETE', '/_plugins/_ml/connectors/foobar'))
            ->willReturn(new Response());

        $this->mlNamespace->deleteConnector([
            'connector_id' => 'foobar'
        ]);
    }

    public function testRegisterModelGroup(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('POST', '/_plugins/_ml/model_groups/_register', [], [
                    'foo' => 'bar',
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->registerModelGroup([
            'body' => [
                'foo' => 'bar',
            ],
        ]);
    }

    public function testGetModelGroups(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('POST', '/_plugins/_ml/model_groups/_search', [], [
                    'query' => [
                        'match_all' => new \StdClass(),
                    ],
                    'size' => 1000,
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->getModelGroups([
            'body' => [
                'query' => [
                    'match_all' => new \StdClass(),
                ],
                'size' => 1000,
            ],
        ]);
    }

    public function testUpdateModelGroup(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('PUT', '/_plugins/_ml/model_groups/foobar', [], [
                    'query' => [
                        'match_all' => new \StdClass(),
                    ],
                    'size' => 1000,
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->updateModelGroup([
            'id' => 'foobar',
            'model_group_id' => 'foobar',
            'body' => [
                'query' => [
                    'match_all' => new \StdClass(),
                ],
                'size' => 1000,
            ],
        ]);
    }

    public function testDeleteModelGroup(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('DELETE', '/_plugins/_ml/model_groups/foobar'))
            ->willReturn(new Response());

        $this->mlNamespace->deleteModelGroup([
            'id' => 'foobar'
        ]);
    }

    public function testRegisterModel(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('POST', '/_plugins/_ml/models/_register', [], [
                    'foo' => 'bar',
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->registerModel([
            'body' => [
                'foo' => 'bar',
            ],
        ]);
    }

    public function testGetModel(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('GET', '/_plugins/_ml/models/foobar_model'))
            ->willReturn(new Response());

        $this->mlNamespace->getModel([
            'id' => 'foobar',
            'model_id' => 'foobar_model'
        ]);
    }

    public function testSearchModels(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('POST', '/_plugins/_ml/models/_search', [], [
                    'query' => [
                        'match_all' => new \StdClass(),
                    ],
                    'size' => 1000,
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->searchModels([
            'body' => [
                'query' => [
                    'match_all' => new \StdClass(),
                ],
                'size' => 1000,
            ],
        ]);
    }

    public function testDeployModel(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('POST', '/_plugins/_ml/models/foobar/_deploy'))
            ->willReturn(new Response());

        $this->mlNamespace->deployModel([
            'model_id' => 'foobar',
        ]);
    }

    public function testUnDeployModel(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('POST', '/_plugins/_ml/models/foobar/_undeploy'))
            ->willReturn(new Response());

        $this->mlNamespace->undeployModel([
            'model_id' => 'foobar',
        ]);
    }

    public function testDeleteModel(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('DELETE', '/_plugins/_ml/models/foobar'))
            ->willReturn(new Response());

        $this->mlNamespace->deleteModel([
            'id' => 'foobar',
        ]);
    }

    public function testPredict(): void
    {
        $this->transport->method('sendRequest')
            ->with(
                new Request('POST', '/_plugins/_ml/_predict/algo/model', [], [
                    'foo' => 'bar',
                ])
            )
            ->willReturn(new Response());

        $this->mlNamespace->predict([
            'id' => 'foobar',
            'body' => [
                'foo' => 'bar',
            ],
            'algorithm_name' => 'algo',
            'model_id' => 'model',
        ]);
    }

    public function testGetTask(): void
    {
        $this->transport->method('sendRequest')
            ->with(new Request('GET', '/_plugins/_ml/tasks/foobar'))
            ->willReturn(new Response());

        $this->mlNamespace->getTask([
            'id' => 'foobar',
        ]);
    }
}
