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

use OpenSearch\Endpoints\AbstractEndpoint;

class AbstractEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \OpenSearch\Endpoints\AbstractEndpoint&\PHPUnit\Framework\MockObject\MockObject
     */
    private $endpoint;

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
     *
     * @covers AbstractEndpoint::setParams
     */
    public function testInvalidParamsCauseErrorsWhenProvidedToSetParams(array $params)
    {
        $this->endpoint->expects($this->once())
            ->method('getParamWhitelist')
            ->willReturn(['one', 'two']);

        $this->expectException(\OpenSearch\Common\Exceptions\UnexpectedValueException::class);

        $this->endpoint->setParams($params);
    }

    /**
     * @covers AbstractEndpoint::setParams
     * @covers AbstractEndpoint::extractOptions
     * @covers AbstractEndpoint::getOptions
     */
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
}
