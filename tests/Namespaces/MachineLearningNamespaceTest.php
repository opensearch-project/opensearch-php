<?php

namespace OpenSearch\Tests\Namespaces;

use OpenSearch\Endpoints\Ml\CreateConnector;
use OpenSearch\Endpoints\Ml\DeleteConnector;
use OpenSearch\Endpoints\Ml\GetConnector;
use OpenSearch\Endpoints\Ml\GetConnectors;
use OpenSearch\Endpoints\Ml\DeleteModelGroup;
use OpenSearch\Endpoints\Ml\GetModelGroups;
use OpenSearch\Endpoints\Ml\RegisterModelGroup;
use OpenSearch\Endpoints\Ml\UpdateModelGroup;
use OpenSearch\Endpoints\Ml\DeleteModel;
use OpenSearch\Endpoints\Ml\DeployModel;
use OpenSearch\Endpoints\Ml\GetModel;
use OpenSearch\Endpoints\Ml\SearchModels;
use OpenSearch\Endpoints\Ml\Predict;
use OpenSearch\Endpoints\Ml\RegisterModel;
use OpenSearch\Endpoints\Ml\UndeployModel;
use OpenSearch\Endpoints\Ml\GetTask;
use OpenSearch\Endpoints\Sql\Query;
use OpenSearch\Namespaces\MlNamespace;
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
class MlNamespaceTest extends TestCase
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

        (new MlNamespace($transport, $func))->createConnector([
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

        (new MlNamespace($transport, $func))->getConnector([
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

        (new MlNamespace($transport, $func))->getConnectors([
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

        (new MlNamespace($transport, $func))->deleteConnector([
          'connector_id' => 'foobar'
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

        (new MlNamespace($transport, $func))->registerModelGroup([
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

        (new MlNamespace($transport, $func))->getModelGroups([
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

        (new MlNamespace($transport, $func))->updateModelGroup([
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

        (new MlNamespace($transport, $func))->deleteModelGroup([
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

        (new MlNamespace($transport, $func))->registerModel([
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

        (new MlNamespace($transport, $func))->getModel([
          'id' => 'foobar',
        ]);
    }

    public function testSearchModels(): void
    {

        $func = static function () {
            return new SearchModels();
        };

        $transport = $this->createMock(Transport::class);

        $transport->method('performRequest')
          ->with('GET', '/_plugins/_ml/models/_search', [], [
            'query' => [
              'match_all' => new \StdClass(),
            ],
            'size' => 1000,
          ]);

        $transport->method('resultOrFuture')
          ->willReturn([]);

        (new MlNamespace($transport, $func))->searchModels([
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

        (new MlNamespace($transport, $func))->deployModel([
          'model_id' => 'foobar',
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

        (new MlNamespace($transport, $func))->undeployModel([
          'model_id' => 'foobar',
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

        (new MlNamespace($transport, $func))->deleteModel([
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

        (new MlNamespace($transport, $func))->predict([
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

        (new MlNamespace($transport, $func))->getTask([
          'id' => 'foobar',
        ]);
    }
}
