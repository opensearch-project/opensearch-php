<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * OpenSearch PHP client
 *
 * @link      https://github.com/opensearch-project/opensearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Tests\Endpoints;

use OpenSearch\EndpointInterface;
use OpenSearch\Endpoints\AbstractEndpoint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OpenSearch\Endpoints\AbstractEndpoint
 */
class AbstractEndpointTest extends TestCase
{
    private EndpointInterface&MockObject $endpoint;

    protected function setUp(): void
    {
        $this->endpoint = $this->getMockForAbstractClass(AbstractEndpoint::class);
    }

    public static function invalidParameters(): array
    {
        return [
            [['invalid' => 10]],
            [['invalid' => 10, 'invalid2' => 'another']],
        ];
    }

    /**
     * @dataProvider invalidParameters
     */
    public function testInvalidParamsCauseErrorsWhenProvidedToSetParams(array $params)
    {
        $this->endpoint->expects($this->once())
            ->method('getParamWhitelist')
            ->willReturn(['one', 'two']);

        $this->expectException(\OpenSearch\Common\Exceptions\UnexpectedValueException::class);

        $this->endpoint->setParams($params);
    }

    public function testOpaqueIdInHeaders()
    {
        $params = ['client' => ['opaqueId' => 'test_id_' . rand(1000, 9999)]];
        $this->endpoint->setParams($params);

        $options = $this->endpoint->getOptions();
        $this->assertArrayHasKey('client', $options);
        $this->assertArrayHasKey('headers', $options['client']);
        $this->assertArrayHasKey('x-opaque-id', $options['client']['headers']);
        $this->assertNotEmpty($options['client']['headers']['x-opaque-id']);
        $this->assertEquals($params['client']['opaqueId'], $options['client']['headers']['x-opaque-id'][0]);
    }

    /**
     * @dataProvider deprecationProvider
     */
    public function testDeprecatedParameterTriggersError(array $input, string $message): void
    {
        $errorMessage = null;
        set_error_handler(static function (int $errno, string $error) use (&$errorMessage): bool {
            $errorMessage = $error;

            return true;
        });

        $endpoint = new TestEndpoint();
        $endpoint->setParams($input);

        static::assertSame($message, $errorMessage);

        restore_error_handler();
    }

    public function testNotDeprecatedParameterTriggersNoError(): void
    {
        $errorMessage = null;
        set_error_handler(static function (int $errno, string $error) use (&$errorMessage): bool {
            $errorMessage = $error;

            return true;
        });

        $endpoint = new TestEndpoint();
        $endpoint->setParams(['normal_one' => 1]);

        static::assertNull($errorMessage);

        restore_error_handler();
    }

    public function deprecationProvider(): iterable
    {
        yield 'replaced without replacement' => [
            [
                'old_without_replacement' => 1,
            ],
            'The parameter "old_without_replacement" is deprecated and will be removed without replacement in the next major version'
        ];

        yield 'replaced with replacement' => [
            [
                'old' => 1,
            ],
            'The parameter "old" is deprecated and will be replaced with parameter "new" in the next major version'
        ];
    }
}

class TestEndpoint extends AbstractEndpoint
{
    public function getParamWhitelist(): array
    {
        return [
            'old',
            'old_without_replacement',
            'new',
            'normal_one'
        ];
    }

    public function getURI(): string
    {
        return '';
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    protected function getParamDeprecation(): array
    {
        return [
            'old' => 'new',
            'old_without_replacement' => null,
        ];
    }
}
