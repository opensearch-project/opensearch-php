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

namespace OpenSearch\Tests\Endpoints;

use OpenSearch\Client;
use OpenSearch\Tests\Utility;

/**
 * Class MlNamespaceIntegrationTest
 *
 * @subpackage Tests\Endpoints
 * @group Integration
 */
class MlNamespaceIntegrationTest extends \PHPUnit\Framework\TestCase
{
    public function testRegisterModelGroup()
    {
        $client = Utility::getClient();

        if (!Utility::isOpenSearchVersionAtLeast($client, '2.8.0')) {
            $this->markTestSkipped('Ml plugin tests require OpenSearch >= 2.8.0');
        }

        $modelGroupResponse = $client->ml()->registerModelGroup([
            'body' => [
                'name' => 'openai_model_group',
                'description' => 'Group containing models for OpenAI',
            ],
        ]);
        $this->assertNotEmpty($modelGroupResponse->getBody()['model_group_id']);
    }

    public function testgetModels()
    {
        $client = Utility::getClient();

        if (!Utility::isOpenSearchVersionAtLeast($client, '2.12.0')) {
            $this->markTestSkipped('Ml plugin tests require OpenSearch >= 2.12.0');
        }

        $modelGroupResponse = $client->ml()->getModels();
        $this->assertNotEmpty($modelGroupResponse->getBody()['hits']);
    }

    public function testsearchModels()
    {
        $client = Utility::getClient();

        if (!Utility::isOpenSearchVersionAtLeast($client, '2.12.0')) {
            $this->markTestSkipped('Ml plugin tests require OpenSearch >= 2.12.0');
        }

        $modelGroupResponse = $client->ml()->searchModels([
            'body' => [
                'query' => [
                  'match_all' => new \StdClass(),
                ],
                'size' => 1000,
              ],
        ]);
        $this->assertNotEmpty($modelGroupResponse->getBody()['hits']);
    }
}
