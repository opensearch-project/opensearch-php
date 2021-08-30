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

namespace Elasticsearch\Tests;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\ElasticsearchException;
use Elasticsearch\Common\Exceptions\RuntimeException;
use Elasticsearch\Tests\ClientBuilder\DummyLogger;
use PHPUnit\Framework\TestCase;

class ClientBuilderTest extends TestCase
{
    public function testClientBuilderThrowsExceptionForIncorrectLoggerClass()
    {
        $this->expectException(\TypeError::class);
        ClientBuilder::create()->setLogger(new DummyLogger);
    }

    public function testClientBuilderThrowsExceptionForIncorrectTracerClass()
    {
        $this->expectException(\TypeError::class);
        ClientBuilder::create()->setTracer(new DummyLogger);
    }

    /**
     * @see https://github.com/elastic/elasticsearch-php/issues/993
     */
    public function testIncludePortInHostHeader()
    {
        $host = "localhost";
        $url = "$host:1234";
        $params = [
            'client' => [
                'verbose' => true
            ]
        ];
        $client = ClientBuilder::create()
            ->setConnectionParams($params)
            ->setHosts([$url])
            ->includePortInHostHeader(true)
            ->build();

        $this->assertInstanceOf(Client::class, $client);

        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            $this->assertTrue(isset($request['request']['headers']['Host'][0]));
            $this->assertEquals($url, $request['request']['headers']['Host'][0]);
        }
    }

    /**
     * @see https://github.com/elastic/elasticsearch-php/issues/993
     */
    public function testNotIncludePortInHostHeaderAsDefault()
    {
        $host = "localhost";
        $url  = "$host:1234";
        $params = [
            'client' => [
                'verbose' => true
            ]
        ];
        $client = ClientBuilder::create()
            ->setConnectionParams($params)
            ->setHosts([$url])
            ->build();

        $this->assertInstanceOf(Client::class, $client);

        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            $this->assertTrue(isset($request['request']['headers']['Host'][0]));
            $this->assertEquals($host, $request['request']['headers']['Host'][0]);
        }
    }

    /**
     * @see https://github.com/elastic/elasticsearch-php/issues/993
     */
    public function testNotIncludePortInHostHeader()
    {
        $host = "localhost";
        $url  = "$host:1234";
        $params = [
            'client' => [
                'verbose' => true
            ]
        ];
        $client = ClientBuilder::create()
            ->setConnectionParams($params)
            ->setHosts([$url])
            ->includePortInHostHeader(false)
            ->build();

        $this->assertInstanceOf(Client::class, $client);

        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            $this->assertTrue(isset($request['request']['headers']['Host'][0]));
            $this->assertEquals($host, $request['request']['headers']['Host'][0]);
        }
    }

    public function getConfig()
    {
        return [
            [[
                'hosts' => ['localhost:9200']
            ]],
            [[
                'hosts'  => ['localhost:9200'],
                'basicAuthentication' => ['username-value', 'password-value']
            ]]
        ];
    }

    /**
     * @dataProvider getConfig
     * @see https://github.com/elastic/elasticsearch-php/issues/1074
     */
    public function testFromConfig(array $params)
    {
        $client = ClientBuilder::fromConfig($params);
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testFromConfigQuiteTrueWithUnknownKey()
    {
        $client = ClientBuilder::fromConfig(
            [
                'hosts' => ['localhost:9200'],
                'foo' => 'bar'
            ],
            true
        );
    }

    public function testFromConfigQuiteFalseWithUnknownKey()
    {
        $this->expectException(RuntimeException::class);
        $client = ClientBuilder::fromConfig(
            [
                'hosts' => ['localhost:9200'],
                'foo' => 'bar'
            ],
            false
        );
    }

    public function testElasticClientMetaHeaderIsSentByDefault()
    {
        $client = ClientBuilder::create()
            ->build();
        $this->assertInstanceOf(Client::class, $client);

        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            $this->assertTrue(isset($request['request']['headers']['x-elastic-client-meta']));
            $this->assertEquals(
                1,
                preg_match(
                    '/^[a-z]{1,}=[a-z0-9\.\-]{1,}(?:,[a-z]{1,}=[a-z0-9\.\-]+)*$/', 
                    $request['request']['headers']['x-elastic-client-meta'][0]
                )
            );
        }    
    }

    public function testElasticClientMetaHeaderIsSentWhenEnabled()
    {
        $client = ClientBuilder::create()
            ->setElasticMetaHeader(true)
            ->build();
        $this->assertInstanceOf(Client::class, $client);

        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            $this->assertTrue(isset($request['request']['headers']['x-elastic-client-meta']));
            $this->assertEquals(
                1,
                preg_match(
                    '/^[a-z]{1,}=[a-z0-9\.\-]{1,}(?:,[a-z]{1,}=[a-z0-9\.\-]+)*$/', 
                    $request['request']['headers']['x-elastic-client-meta'][0]
                )
            );
        }    
    }

    public function testElasticClientMetaHeaderIsNotSentWhenDisabled()
    {
        $client = ClientBuilder::create()
            ->setElasticMetaHeader(false)
            ->build();
        $this->assertInstanceOf(Client::class, $client);

        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            $this->assertFalse(isset($request['request']['headers']['x-elastic-client-meta']));
        }    
    }

    public function getCompatibilityHeaders()
    {
        return [
            ['true', true],
            ['1', true],
            ['false', false],
            ['0', false]
        ];
    }

    /**
     * @dataProvider getCompatibilityHeaders
     */
    public function testCompatibilityHeader($env, $compatibility)
    {
        putenv("ELASTIC_CLIENT_APIVERSIONING=$env");

        $client = ClientBuilder::create()
            ->build();
        
        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            if ($compatibility) {
                $this->assertContains('application/vnd.elasticsearch+json;compatible-with=7', $request['request']['headers']['Content-Type']);
                $this->assertContains('application/vnd.elasticsearch+json;compatible-with=7', $request['request']['headers']['Accept']);
            } else {
                $this->assertNotContains('application/vnd.elasticsearch+json;compatible-with=7', $request['request']['headers']['Content-Type']);
                $this->assertNotContains('application/vnd.elasticsearch+json;compatible-with=7', $request['request']['headers']['Accept']);
            }
        }    
    }

    public function testCompatibilityHeaderDefaultIsOff()
    {
        $client = ClientBuilder::create()
            ->build();
        
        try {
            $result = $client->info();
        } catch (ElasticsearchException $e) {
            $request = $client->transport->getLastConnection()->getLastRequestInfo();
            $this->assertNotContains('application/vnd.elasticsearch+json;compatible-with=7', $request['request']['headers']['Content-Type']);
            $this->assertNotContains('application/vnd.elasticsearch+json;compatible-with=7', $request['request']['headers']['Accept']);
        }    
    }
}
