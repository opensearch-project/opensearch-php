<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Exception;

use OpenSearch\Exception\BadRequestHttpException;
use OpenSearch\Exception\HttpExceptionFactory;
use OpenSearch\Exception\InternalServerErrorHttpException;
use OpenSearch\Exception\NoDocumentsToGetException;
use OpenSearch\Exception\NoShardAvailableException;
use OpenSearch\Exception\RoutingMissingException;
use OpenSearch\Exception\ScriptLangNotSupportedException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests the HTTP exception factory.
 */
#[CoversClass(HttpExceptionFactory::class)]
class HttpExceptionFactoryTest extends TestCase
{
    public function testBadRequest(): void
    {
        $exception = HttpExceptionFactory::create(400, 'error message', ['foo' => 'bar']);
        $this->assertInstanceOf(BadRequestHttpException::class, $exception);
        $this->assertEquals('error message', $exception->getMessage());
        $this->assertEquals(['foo' => 'bar'], $exception->getHeaders());
    }

    public function testScriptLangNotSupportedException(): void
    {
        $exception = HttpExceptionFactory::create(400, 'script_lang not supported');
        $this->assertInstanceOf(ScriptLangNotSupportedException::class, $exception);
        $this->assertEquals('script_lang not supported', $exception->getMessage());
    }

    public function testInternalServerError()
    {
        $exception = HttpExceptionFactory::create(500, 'error message', ['foo' => 'bar']);
        $this->assertInstanceOf(InternalServerErrorHttpException::class, $exception);
        $this->assertEquals('error message', $exception->getMessage());
        $this->assertEquals(['foo' => 'bar'], $exception->getHeaders());
    }

    public function testRoutingMissingException()
    {
        $exception = HttpExceptionFactory::create(500, 'RoutingMissingException');
        $this->assertInstanceOf(RoutingMissingException::class, $exception);
        $this->assertEquals('RoutingMissingException', $exception->getMessage());
    }

    public function testNoDocumentsToGetException()
    {
        $exception = HttpExceptionFactory::create(500, 'ActionRequestValidationException foo bar no documents to get');
        $this->assertInstanceOf(NoDocumentsToGetException::class, $exception);
        $this->assertEquals('ActionRequestValidationException foo bar no documents to get', $exception->getMessage());
    }

    public function testNoShardAvailableActionException()
    {
        $exception = HttpExceptionFactory::create(500, 'NoShardAvailableActionException foo bar');
        $this->assertInstanceOf(NoShardAvailableException::class, $exception);
        $this->assertEquals('NoShardAvailableActionException foo bar', $exception->getMessage());
    }

}
