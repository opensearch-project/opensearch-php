<?php

namespace OpenSearch\Aws;

use Aws\Credentials\CredentialsInterface;
use Aws\Signature\SignatureInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A decorator client that signs requests using the provided AWS credentials and signer.
 */
class SigningClientDecorator implements ClientInterface
{
    /**
     * @param ClientInterface $inner The client to decorate.
     * @param CredentialsInterface $credentials The AWS credentials to use for signing requests.
     * @param SignatureInterface $signer The AWS signer to use for signing requests.
     * @param array $headers Additional headers to add to the request. `Host` is required.
     * @return void
     */
    public function __construct(
        protected ClientInterface $inner,
        protected CredentialsInterface $credentials,
        protected SignatureInterface $signer,
        protected array $headers = []
    ) {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        foreach ($this->headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if (empty($request->getHeaderLine('Host'))) {
            throw new \RuntimeException('Missing Host header.');
        }

        $request = $request->withHeader('x-amz-content-sha256', hash('sha256', (string) $request->getBody()));
        $request = $this->signer->signRequest($request, $this->credentials);
        return $this->inner->sendRequest($request);
    }
}
