<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Aws;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\CredentialsInterface;
use Aws\Exception\CredentialsException;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use OpenSearch\Aws\SigningClientDecorator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Tests the signing client decorator.
 */
#[CoversClass(SigningClientDecorator::class)]
class SigningClientDecoratorTest extends TestCase
{
    /**
     * Test that the decorator signs the request with deprecated arguments.
     */
    public function testSendRequestWithDeprecatedArgs(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $credentials = $this->createMock(CredentialsInterface::class);
        $signer = new SignatureV4('es', 'us-east-1');

        $client->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->callback(function (Request $req): bool {
                    $this->assertEquals('server:443', $req->getHeaderLine('Host'));
                    $this->assertEquals('GET', $req->getMethod());
                    $this->assertTrue($req->hasHeader('Host'));
                    $this->assertTrue($req->hasHeader('Authorization'));
                    $this->assertTrue($req->hasHeader('x-amz-content-sha256'));
                    $this->assertTrue($req->hasHeader('x-amz-date'));
                    return true;
                })
            )
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->expectUserDeprecationMessage('Passing ' . CredentialsInterface::class . ' as the $credentialProvider param in ' . SigningClientDecorator::class . '::__construct() is deprecated in 2.8.0 and will be removed in 3.0.0. Pass a callable instead.');

        $decorator = new SigningClientDecorator($client, $credentials, $signer, ['Host' => 'server:443']);

        $request = new Request('GET', 'http://localhost:9200/_search');
        $decorator->sendRequest($request);
    }

    /**
     * Test that the decorator signs the request.
     */
    public function testSendRequest(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $credentials = $this->createMock(CredentialsInterface::class);
        $promise = $this->createMock(PromiseInterface::class);
        $credentialProvider = function () use ($promise) {
            return $promise;
        };
        $signer = new SignatureV4('es', 'us-east-1');

        $client->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->callback(function (Request $req): bool {
                    $this->assertEquals('server:443', $req->getHeaderLine('Host'));
                    $this->assertEquals('GET', $req->getMethod());
                    $this->assertTrue($req->hasHeader('Host'));
                    $this->assertTrue($req->hasHeader('Authorization'));
                    $this->assertTrue($req->hasHeader('x-amz-content-sha256'));
                    $this->assertTrue($req->hasHeader('x-amz-date'));
                    return true;
                })
            )
            ->willReturn($this->createMock(ResponseInterface::class));

        $promise->expects($this->once())
            ->method('wait')
            ->willReturn($credentials);

        $decorator = new SigningClientDecorator($client, $credentialProvider, $signer, ['Host' => 'server:443']);
        $request = new Request('GET', 'http://localhost:9200/_search');
        $decorator->sendRequest($request);
    }

    public function testSendRequestWithEmptyHostHeader(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Missing Host header.');
        $client = $this->createMock(ClientInterface::class);
        $credentials = $this->createMock(CredentialsInterface::class);
        $credentialProvider = CredentialProvider::fromCredentials($credentials);
        $signer = $this->createMock(SignatureV4::class);
        $decorator = new SigningClientDecorator($client, $credentialProvider, $signer, []);
        $request = new Request('GET', 'http://localhost:9200/_search', ['Host' => '']);
        $decorator->sendRequest($request);
    }

    public function testSendRequestWithCredentialsException(): void
    {
        $this->expectException(CredentialsException::class);

        $client = $this->createMock(ClientInterface::class);
        $promise = $this->createMock(PromiseInterface::class);
        $credentialProvider = function () use ($promise) {
            return $promise;
        };
        $signer = new SignatureV4('es', 'us-east-1');
        $logger = $this->createMock(LoggerInterface::class);

        $promise->expects($this->once())
            ->method('wait')
            ->willThrowException(new CredentialsException());

        $logger->expects($this->once())->method('error');

        $decorator = new SigningClientDecorator($client, $credentialProvider, $signer, ['Host' => 'server:443']);
        $decorator->setLogger($logger);
        $request = new Request('GET', 'http://localhost:9200/_search', ['Host' => '']);
        $decorator->sendRequest($request);
    }
}
