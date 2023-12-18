<?php

namespace OpenSearch\Tests\Namespaces;

use OpenSearch\Endpoints\MachineLearning\Connectors\CreateConnector;
use OpenSearch\Endpoints\MachineLearning\Connectors\DeleteConnector;
use OpenSearch\Endpoints\MachineLearning\Connectors\GetConnector;
use OpenSearch\Endpoints\MachineLearning\Connectors\GetConnectors;
use OpenSearch\Endpoints\MachineLearning\ModelGroups\DeleteModelGroup;
use OpenSearch\Endpoints\MachineLearning\ModelGroups\GetModelGroups;
use OpenSearch\Endpoints\MachineLearning\ModelGroups\RegisterModelGroup;
use OpenSearch\Endpoints\MachineLearning\ModelGroups\UpdateModelGroup;
use OpenSearch\Endpoints\MachineLearning\Models\DeleteModel;
use OpenSearch\Endpoints\MachineLearning\Models\DeployModel;
use OpenSearch\Endpoints\MachineLearning\Models\GetModel;
use OpenSearch\Endpoints\MachineLearning\Models\GetModels;
use OpenSearch\Endpoints\MachineLearning\Models\Predict;
use OpenSearch\Endpoints\MachineLearning\Models\RegisterModel;
use OpenSearch\Endpoints\MachineLearning\Models\UndeployModel;
use OpenSearch\Endpoints\MachineLearning\Tasks\GetTask;
use OpenSearch\Endpoints\Sql\Query;
use OpenSearch\Namespaces\MachineLearningNamespace;
use OpenSearch\Namespaces\SqlNamespace;
use OpenSearch\Transport;
use PHPUnit\Framework\TestCase;

/**
 * Copyright OpenSearch Contributors
 *  SPDX-License-Identifier: Apache-2.0
 *
 *  The OpenSearch Contributors require contributions made to
 *  this file be licensed under the Apache-2.0 license or a
 *  compatible open source license.
 */
class MachineLearningNamespaceTest extends TestCase
{
    public function testCreatingConnector(): void
    {

        $func = static function () {
            return new CreateConnector();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/connectors/_create', [], [
              'foo' => 'bar',
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->createConnector([
          'body' => [
            'foo' => 'bar',
          ],
        ]);
    }

    public function testGetConnector(): void
    {

        $func = static function () {
            return new GetConnector();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('GET', '/_plugins/_ml/connectors/foobar', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->getConnector([
          'id' => 'foobar'
        ]);
    }

    public function testGetConnectors(): void
    {

        $func = static function () {
            return new GetConnectors();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/connectors/_search', [], [
            'query' => [
              'match_all' => new \StdClass(),
            ],
            'size' => 1000,
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->getConnectors([
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

        $func = static function () {
            return new DeleteConnector();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('DELETE', '/_plugins/_ml/connectors/foobar', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->deleteConnector([
          'id' => 'foobar'
        ]);
    }

    public function testRegisterModelGroup(): void
    {

        $func = static function () {
            return new RegisterModelGroup();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/model_groups/_register', [], [
            'foo' => 'bar',
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->registerModelGroup([
          'body' => [
            'foo' => 'bar',
          ],
        ]);
    }

    public function testGetModelGroups(): void
    {

        $func = static function () {
            return new GetModelGroups();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/model_groups/_search', [], [
            'query' => [
              'match_all' => new \StdClass(),
            ],
            'size' => 1000,
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->getModelGroups([
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

        $func = static function () {
            return new UpdateModelGroup();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('PUT', '/_plugins/_ml/model_groups/foobar', [], [
            'query' => [
              'match_all' => new \StdClass(),
            ],
            'size' => 1000,
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->updateModelGroup([
          'id' => 'foobar',
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

        $func = static function () {
            return new DeleteModelGroup();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('DELETE', '/_plugins/_ml/model_groups/foobar', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->deleteModelGroup([
          'id' => 'foobar'
        ]);
    }

    public function testRegisterModel(): void
    {

        $func = static function () {
            return new RegisterModel();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/models/_register', [], [
            'foo' => 'bar',
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->registerModel([
          'body' => [
            'foo' => 'bar',
          ],
        ]);
    }

    public function testGetModel(): void
    {

        $func = static function () {
            return new GetModel();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('GET', '/_plugins/_ml/models/foobar', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->getModel([
          'id' => 'foobar',
        ]);
    }

    public function testGetModels(): void
    {

        $func = static function () {
            return new GetModels();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/models/_search', [], [
            'query' => [
              'match_all' => new \StdClass(),
            ],
            'size' => 1000,
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->getModels([
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

        $func = static function () {
            return new DeployModel();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/models/foobar/_deploy', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->deployModel([
          'id' => 'foobar',
        ]);
    }

    public function testUnDeployModel(): void
    {

        $func = static function () {
            return new UndeployModel();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/models/foobar/_undeploy', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->undeployModel([
          'id' => 'foobar',
        ]);
    }

    public function testDeleteModel(): void
    {

        $func = static function () {
            return new DeleteModel();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('DELETE', '/_plugins/_ml/models/foobar', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->deleteModel([
          'id' => 'foobar',
        ]);
    }

    public function testPredict(): void
    {

        $func = static function () {
            return new Predict();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('POST', '/_plugins/_ml/models/foobar/_predict', [], [
            'foo' => 'bar',
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->predict([
          'id' => 'foobar',
          'body' => [
            'foo' => 'bar',
          ]
        ]);
    }

    public function testGetTask(): void
    {

        $func = static function () {
            return new GetTask();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('GET', '/_plugins/_ml/tasks/foobar', [], null);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MachineLearningNamespace($transport, $func))->getTask([
          'id' => 'foobar',
        ]);
    }
}
