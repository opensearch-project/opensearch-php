<?php

declare(strict_types=1);

namespace OpenSearch\Handlers;

use Aws\Credentials\CredentialProvider;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use OpenSearch\ClientBuilder;
use Psr\Http\Message\RequestInterface;
use RuntimeException;

/**
 * @phpstan-type RingPhpRequest array{http_method: string, scheme: string, uri: string, query_string?: string, version?: string, headers: array<string, list<string>>, body: string|resource|null, client?: array<string, mixed>}
 */
class SigV4Handler
{
    /**
     * @var SignatureV4
     */
    private $signer;
    /**
     * @var callable
     */
    private $credentialProvider;
    /**
     * @var callable
     */
    private $wrappedHandler;

    /**
     * A handler that applies an AWS V4 signature before dispatching requests.
     *
     * @param string        $region                 The region of your Amazon
     *                                              OpenSearch Service domain
     * @param string        $service                The Service of your Amazon
     *                                              OpenSearch Service domain
     * @param callable|null $credentialProvider     A callable that returns a
     *                                              promise that is fulfilled
     *                                              with an instance of
     *                                              Aws\Credentials\Credentials
     * @param callable|null $wrappedHandler         A RingPHP handler
     */
    public function __construct(
        string $region,
        string $service,
        ?callable $credentialProvider = null,
        ?callable $wrappedHandler = null
    ) {
        self::assertDependenciesInstalled();
        $this->signer = new SignatureV4($service, $region);
        $this->wrappedHandler = $wrappedHandler
            ?: ClientBuilder::defaultHandler();
        $this->credentialProvider = $credentialProvider
            ?: CredentialProvider::defaultProvider();
    }

    /**
     * @phpstan-param RingPhpRequest $request
     */
    public function __invoke(array $request)
    {
        $creds = call_user_func($this->credentialProvider)->wait();

        $psr7Request = $this->createPsr7Request($request);
        $psr7Request = $psr7Request->withHeader('x-amz-content-sha256', Utils::hash($psr7Request->getBody(), 'sha256'));
        $signedRequest = $this->signer
            ->signRequest($psr7Request, $creds);
        return call_user_func($this->wrappedHandler, $this->createRingRequest($signedRequest, $request));
    }

    public static function assertDependenciesInstalled(): void
    {
        if (!class_exists(SignatureV4::class)) {
            throw new RuntimeException(
                'The AWS SDK for PHP must be installed in order to use the SigV4 signing handler'
            );
        }
    }

    /**
     * @phpstan-param RingPhpRequest $ringPhpRequest
     */
    private function createPsr7Request(array $ringPhpRequest): Request
    {
        // fix for uppercase 'Host' array key in elasticsearch-php 5.3.1 and backward compatible
        // https://github.com/aws/aws-sdk-php/issues/1225
        $hostKey = isset($ringPhpRequest['headers']['Host']) ? 'Host' : 'host';

        // Amazon ES/OS listens on standard ports (443 for HTTPS, 80 for HTTP).
        // Consequently, the port should be stripped from the host header.
        $parsedUrl = parse_url($ringPhpRequest['headers'][$hostKey][0]);
        if (isset($parsedUrl['host'])) {
            $ringPhpRequest['headers'][$hostKey][0] = $parsedUrl['host'];
        }

        // Create a PSR-7 URI from the array passed to the handler
        $uri = (new Uri($ringPhpRequest['uri']))
            ->withScheme($ringPhpRequest['scheme'])
            ->withHost($ringPhpRequest['headers'][$hostKey][0]);
        if (isset($ringPhpRequest['query_string'])) {
            $uri = $uri->withQuery($ringPhpRequest['query_string']);
        }

        // Create a PSR-7 request from the array passed to the handler
        return new Request(
            $ringPhpRequest['http_method'],
            $uri,
            $ringPhpRequest['headers'],
            $ringPhpRequest['body']
        );
    }

    /**
     * @phpstan-param RingPhpRequest $originalRequest
     *
     * @phpstan-return RingPhpRequest
     */
    private function createRingRequest(RequestInterface $request, array $originalRequest): array
    {
        $uri = $request->getUri();
        $body = (string) $request->getBody();

        // RingPHP currently expects empty message bodies to be null:
        // https://github.com/guzzle/RingPHP/blob/4c8fe4c48a0fb7cc5e41ef529e43fecd6da4d539/src/Client/CurlFactory.php#L202
        if (empty($body)) {
            $body = null;
        }

        // Reset the explicit port in the URL
        $client = $originalRequest['client'];
        unset($client['curl'][CURLOPT_PORT]);

        $ringRequest = [
            'http_method' => $request->getMethod(),
            'scheme' => $uri->getScheme(),
            'uri' => $uri->getPath(),
            'body' => $body,
            'headers' => $request->getHeaders(),
            'client' => $client
        ];
        if ($uri->getQuery()) {
            $ringRequest['query_string'] = $uri->getQuery();
        }

        return $ringRequest;
    }
}
