<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Aws;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\CredentialsInterface;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Psr7\Request;
use OpenSearch\Aws\RefreshableSigningClientDecorator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the refreshable signing client decorator.
 */
#[CoversClass(RefreshableSigningClientDecorator::class)]
class RefreshableSigningClientDecoratorTest extends TestCase
{
    /**
     * Test that the decorator signs the request.
     */
    public function testSendRequest()
    {
        $client = $this->createMock(ClientInterface::class);
        $credentials = $this->createMock(CredentialsInterface::class);
        $credentialProvider = CredentialProvider::fromCredentials($credentials);
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

        $decorator = new RefreshableSigningClientDecorator($client, $credentialProvider, $signer, ['Host' => 'server:443']);
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
        $decorator = new RefreshableSigningClientDecorator($client, $credentialProvider, $signer, []);
        $request = new Request('GET', 'http://localhost:9200/_search', ['Host' => '']);
        $decorator->sendRequest($request);
    }
}
