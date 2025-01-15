<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Exception;

use OpenSearch\Exception\ErrorMessageExtractor;
use OpenSearch\Serializers\SmartSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Tests the error message extractor.
 *
 * @coversDefaultClass \OpenSearch\Exception\ErrorMessageExtractor
 */
class ErrorMessageExtractorTest extends TestCase
{
    public function testUnstructured(): void
    {
        $data = ['foo' => 'error message'];
        $message = ErrorMessageExtractor::extractErrorMessage($data);
        $this->assertEquals('{"foo":"error message"}', $message);
    }

    public function testStringData(): void
    {
        $data = 'error message';
        $message = ErrorMessageExtractor::extractErrorMessage($data);
        $this->assertEquals('error message', $message);
    }

    public function testNullData(): void
    {
        $data = null;
        $message = ErrorMessageExtractor::extractErrorMessage($data);
        $this->assertNull($message);
    }

    public function testLegacyErrorAsArray(): void
    {
        $data = [
            'error' => [
                'foo' => 'error message'
            ]
        ];
        $message = ErrorMessageExtractor::extractErrorMessage($data);
        $this->assertEquals('{"foo":"error message"}', $message);
    }

    public function testLegacyErrorAsString(): void
    {
        $data = [
            'error' => 'error message'
        ];
        $message = ErrorMessageExtractor::extractErrorMessage($data);
        $this->assertEquals('error message', $message);
    }

    public function testExtractMessageRootCause(): void
    {
        $data = [
            'error' => [
                'root_cause' => [
                    [
                        'type' => 'root_cause_type',
                        'reason' => 'root_cause_reason'
                    ]
                ],
                'type' => 'type',
                'reason' => 'reason'
            ],
            'status' => 400
        ];

        $message = ErrorMessageExtractor::extractErrorMessage($data);

        $this->assertEquals('root_cause_type: root_cause_reason', $message);
    }


}
