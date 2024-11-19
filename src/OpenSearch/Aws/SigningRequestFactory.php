<?php

namespace OpenSearch\Aws;

use Aws\Credentials\CredentialsInterface;
use Aws\Signature\SignatureInterface;
use OpenSearch\RequestFactory;
use OpenSearch\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

/**
 * A decorator request factory that signs requests using the provided AWS credentials and signer.
 */
class SigningRequestFactory implements RequestFactoryInterface
{
    public function __construct(
        protected RequestFactory $requestFactory,
        protected CredentialsInterface $credentials,
        protected SignatureInterface $signer,
    ) {
        if (!class_exists(SignatureInterface::class)) {
            throw new \RuntimeException(
                'The AWS SDK for PHP must be installed in order to use the AWS signing request factory.'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(
        string $method,
        string $uri,
        array $params = [],
        array|string|null $body = null,
        array $headers = [],
    ): RequestInterface {
        $request = $this->requestFactory->createRequest($method, $uri, $params, $body, $headers);
        $request = $request->withHeader('x-amz-content-sha256', hash('sha256', (string) $request->getBody()));
        return $this->signer->signRequest($request, $this->credentials);
    }

}
