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
    public function __construct(
        protected ClientInterface $inner,
        protected CredentialsInterface $credentials,
        protected SignatureInterface $signer,
    ) {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request = $request->withHeader('x-amz-content-sha256', hash('sha256', (string) $request->getBody()));
        $request = $this->signer->signRequest($request, $this->credentials);
        return $this->inner->sendRequest($request);
    }
}
