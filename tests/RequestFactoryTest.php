<?php

declare(strict_types=1);

namespace OpenSearch\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use OpenSearch\RequestFactory;
use OpenSearch\Serializers\SerializerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests the request factory.
 *
 * @coversDefaultClass \OpenSearch\RequestFactory
 */
class RequestFactoryTest extends TestCase
{
    public function testBoolean(): void
    {
        $httpFactory = new HttpFactory();
        $serializer = $this->createMock(SerializerInterface::class);

        $factory = new RequestFactory($httpFactory, $httpFactory, $httpFactory, $serializer);

        $params = ['foo' => true, 'bar' => false];
        $request = $factory->createRequest('GET', 'http://localhost:9200/_search', $params);

        $this->assertEquals('bar=false&foo=true', $request->getUri()->getQuery());
    }
}
