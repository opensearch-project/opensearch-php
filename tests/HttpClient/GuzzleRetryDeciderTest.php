<?php

declare(strict_types=1);

namespace OpenSearch\Tests\HttpClient;

use ColinODell\PsrTestLogger\TestLogger;
use GuzzleHttp\Exception\ConnectException;
use OpenSearch\HttpClient\GuzzleRetryDecider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Test the Guzzle retry decider.
 */
#[CoversClass(GuzzleRetryDecider::class)]
class GuzzleRetryDeciderTest extends TestCase
{
    public function testMaxRetriesDoesNotRetry(): void
    {
        $decider = new GuzzleRetryDecider(2);
        $this->assertFalse($decider(2, null, null, null));
    }

    public function test500orNoExceptionDoesNotRetry(): void
    {
        $decider = new GuzzleRetryDecider(2);
        $this->assertFalse($decider(0, null, null, null));
    }

    public function testConnectExceptionRetries(): void
    {
        $logger = new TestLogger();
        $decider = new GuzzleRetryDecider(2, $logger);
        $this->assertTrue($decider(0, null, null, new ConnectException('Error', $this->createMock(RequestInterface::class))));
        $this->assertTrue($logger->hasWarning([
            'level' => 'warning',
            'message' => 'Retrying request {retries} of {maxRetries}: {exception}',
            'context' => [
                'retries' => 0,
                'maxRetries' => 2,
                'exception' => 'Error',
            ],
        ]));
    }

    public function testStatus500Retries(): void
    {
        $logger = new TestLogger();
        $decider = new GuzzleRetryDecider(2, $logger);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);

        $this->assertTrue($decider(0, null, $response, null));
        $this->assertTrue($logger->hasWarning([
            'level' => 'warning',
            'message' => 'Retrying request {retries} of {maxRetries}: Status code {status}',
            'context' => [
                'retries' => 0,
                'maxRetries' => 2,
                'status' => 500,
            ],
        ]));
    }
}
