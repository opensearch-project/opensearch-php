<?php

declare(strict_types=1);

namespace OpenSearch\Tests\Aws;

use Aws\Credentials\CredentialsInterface;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Psr7\Request;
use OpenSearch\Aws\SigningClientDecorator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the signing client decorator.
 *
 * @coversDefaultClass \OpenSearch\Aws\SigningClientDecorator
 */
class SigningClientDecoratorTest extends TestCase
{
    /**
     * Test that the decorator signs the request.
     *
     * @covers ::sendRequest
     */
    public function testSendRequest()
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

        $decorator = new SigningClientDecorator($client, $credentials, $signer, ['Host' => 'server:443']);
        $request = new Request('GET', 'http://localhost:9200/_search');
        $decorator->sendRequest($request);
    }
}
