<?php

namespace OpenSearch;

class ClientFactory
{
    public function __construct(
        protected TransportInterface $transport,
        protected EndpointFactoryInterface $endpointFactory,
        protected array $registeredNamespaces = [],
    ) {
    }

    public function createClient(): Client
    {
        return new Client($this->transport, $this->endpointFactory, $this->registeredNamespaces);
    }

}
