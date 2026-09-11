<?php

declare(strict_types=1);

namespace OpenSearch\Aws;

use Psr\Http\Client\ClientInterface;

/**
 * A factory for creating an HTTP Client that signs requests using AWS refreshable credentials.
 */
class RefreshableSigningClientFactory extends SigningClientFactory
{
    /**
     * Creates a new signing client.
     *
     * @param ClientInterface $innerClient
     *   The decorated inner HTTP client.
     * @param array<string,string> $options
     *   The AWS auth options.
     */
    public function create(ClientInterface $innerClient, array $options): ClientInterface
    {
        if (!isset($options['host'])) {
            throw new \InvalidArgumentException('The host option is required.');
        }

        // Get the credentials.
        $provider = $this->getCredentialProvider($options);

        // Get the signer.
        $signer = $this->getSigner($options);

        return new RefreshableSigningClientDecorator($innerClient, $provider, $signer, ['host' => $options['host']]);
    }
}
