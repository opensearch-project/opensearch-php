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

namespace OpenSearch\Tests;

use OpenSearch;
use OpenSearch\ClientBuilder;
use OpenSearch\Endpoints\AbstractEndpoint;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Transport;
use Mockery as m;

/**
 * Class RegisteredNamespaceTest
 *
 * @subpackage Tests
 */
class RegisteredNamespaceTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testRegisteringNamespace()
    {
        $builder = new FooNamespaceBuilder();
        $client = ClientBuilder::create()->registerNamespace($builder)->build();
        $this->assertSame("123", $client->foo()->fooMethod());
    }

    public function testNonExistingNamespace()
    {
        $builder = new FooNamespaceBuilder();
        $client = ClientBuilder::create()->registerNamespace($builder)->build();

        $this->expectException(\OpenSearch\Common\Exceptions\BadMethodCallException::class);
        $this->expectExceptionMessage('Namespace [bar] not found');

        $client->bar()->fooMethod();
    }
}

// @codingStandardsIgnoreStart "Each class must be in a file by itself" - not worth the extra work here
class FooNamespaceBuilder implements OpenSearch\Namespaces\NamespaceBuilderInterface
{
    public function getName(): string
    {
        return "foo";
    }

    public function getObject(Transport $transport, SerializerInterface $serializer)
    {
        return new FooNamespace();
    }
}

class FooNamespace
{
    public function fooMethod()
    {
        return "123";
    }
}
// @codingStandardsIgnoreEnd
