<?php

namespace OpenSearch\Aws;

use Aws\Exception\CredentialsException;
use Aws\Signature\SignatureInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * A refreshable decorator client that signs requests using the provided AWS credentials and signer.
 */
class RefreshableSigningClientDecorator implements ClientInterface
{
    protected \Closure $credentialProvider;

    /**
     * @param ClientInterface $inner The client to decorate.
     * @param callable $credentialProvider AWS credential provider. Returns a promise resolving to CredentialsInterface.
     * @param SignatureInterface $signer The AWS signer to use for signing requests.
     * @param array $headers Additional headers to add to the request. `Host` is required.
     * @param LoggerInterface|null $logger Logger for logging request signing activities.
     * @return void
     */
    public function __construct(
        protected ClientInterface $inner,
        callable $credentialProvider,
        protected SignatureInterface $signer,
        protected array $headers = [],
        protected ?LoggerInterface $logger = null,
    ) {
        $this->credentialProvider = \Closure::fromCallable($credentialProvider);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        foreach ($this->headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if (empty($request->getHeaderLine('Host'))) {
            throw new \RuntimeException('Missing Host header.');
        }

        try {
            $credentials = ($this->credentialProvider)()->wait();
        } catch (CredentialsException $e) {
            $this->logger?->error('Failed to get AWS credentials: @message', ['@message' => $e->getMessage()]);
            throw $e;
        }

        $request = $request->withHeader('x-amz-content-sha256', hash('sha256', (string) $request->getBody()));
        $request = $this->signer->signRequest($request, $credentials);
        return $this->inner->sendRequest($request);
    }
}
