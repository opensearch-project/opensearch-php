<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * OpenSearch PHP client
 *
 * @link      https://github.com/opensearch-project/opensearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Credentials\CredentialsInterface;
use GuzzleHttp\Ring\Client\CurlHandler;
use GuzzleHttp\Ring\Client\CurlMultiHandler;
use GuzzleHttp\Ring\Client\Middleware;
use OpenSearch\Common\Exceptions\AuthenticationConfigException;
use OpenSearch\Common\Exceptions\InvalidArgumentException;
use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\ConnectionPool\AbstractConnectionPool;
use OpenSearch\ConnectionPool\Selectors\RoundRobinSelector;
use OpenSearch\ConnectionPool\Selectors\SelectorInterface;
use OpenSearch\ConnectionPool\StaticNoPingConnectionPool;
use OpenSearch\Connections\ConnectionFactory;
use OpenSearch\Connections\ConnectionFactoryInterface;
use OpenSearch\Connections\ConnectionInterface;
use OpenSearch\Handlers\SigV4Handler;
use OpenSearch\Namespaces\NamespaceBuilderInterface;
use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Serializers\SmartSerializer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionClass;

// @phpstan-ignore classConstant.deprecatedClass
@trigger_error(ClientBuilder::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 */
class ClientBuilder
{
    public const ALLOWED_METHODS_FROM_CONFIG = ['includePortInHostHeader'];

    /**
     * @var Transport|null
     */
    private $transport;

    private ?EndpointFactoryInterface $endpointFactory = null;

    /**
     * @var NamespaceBuilderInterface[]
     */
    private $registeredNamespacesBuilders = [];

    /**
     * @var ConnectionFactoryInterface|null
     */
    private $connectionFactory;

    /**
     * @var callable|null
     */
    private $handler;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var LoggerInterface|null
     */
    private $tracer;

    /**
     * @var string|AbstractConnectionPool
     */
    private $connectionPool = StaticNoPingConnectionPool::class;

    /**
     * @var string|SerializerInterface|null
     */
    private $serializer = SmartSerializer::class;

    /**
     * @var string|SelectorInterface|null
     */
    private $selector = RoundRobinSelector::class;

    /**
     * @var array
     */
    private $connectionPoolArgs = [
        'randomizeHosts' => true
    ];

    /**
     * @var array|null
     */
    private $hosts;

    /**
     * @var array
     */
    private $connectionParams;

    /**
     * @var int|null
     */
    private $retries;

    /**
     * @var null|callable
     */
    private $sigV4CredentialProvider;

    /**
     * @var null|string
     */
    private $sigV4Region;

    /**
     * @var null|string
     */
    private $sigV4Service;

    /**
     * @var bool
     */
    private $sniffOnStart = false;

    /**
     * @var null|array
     */
    private $sslCert;

    /**
     * @var null|array
     */
    private $sslKey;

    /**
     * @var null|bool|string
     */
    private $sslVerification;

    /**
     * @var bool
     */
    private $includePortInHostHeader = false;

    /**
     * @var string|null
     */
    private $basicAuthentication = null;

    /**
     * Create an instance of ClientBuilder
     */
    public static function create(): ClientBuilder
    {
        return new self();
    }

    /**
     * Can supply first param to Client::__construct() when invoking manually or with dependency injection
     */
    public function getTransport(): Transport
    {
        return $this->transport;
    }

    /**
     * Can supply second param to Client::__construct() when invoking manually or with dependency injection
     *
     * @deprecated in 2.4.0 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::getEndpointFactory() instead.
     */
    public function getEndpoint(): callable
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.4.0 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::getEndpointFactory() instead.', E_USER_DEPRECATED);
        return fn ($c) => $this->endpointFactory->getEndpoint('OpenSearch\\Endpoints\\' . $c);
    }

    /**
     * Can supply third param to Client::__construct() when invoking manually or with dependency injection
     *
     * @return NamespaceBuilderInterface[]
     */
    public function getRegisteredNamespacesBuilders(): array
    {
        return $this->registeredNamespacesBuilders;
    }

    /**
     * Build a new client from the provided config.  Hash keys
     * should correspond to the method name e.g. ['connectionPool']
     * corresponds to setConnectionPool().
     *
     * Missing keys will use the default for that setting if applicable
     *
     * Unknown keys will throw an exception by default, but this can be silenced
     * by setting `quiet` to true
     *
     * @param  array $config
     * @param  bool $quiet False if unknown settings throw exception, true to silently
     *                     ignore unknown settings
     * @throws Common\Exceptions\RuntimeException
     */
    public static function fromConfig(array $config, bool $quiet = false): Client
    {
        $builder = new self();
        foreach ($config as $key => $value) {
            $method = in_array($key, self::ALLOWED_METHODS_FROM_CONFIG, true) ? $key : "set$key";
            $reflection = new ReflectionClass($builder);
            if ($reflection->hasMethod($method)) {
                $func = $reflection->getMethod($method);
                if ($func->getNumberOfParameters() > 1) {
                    $builder->$method(...$value);
                } else {
                    $builder->$method($value);
                }
                unset($config[$key]);
            }
        }

        if ($quiet === false && count($config) > 0) {
            $unknown = implode(array_keys($config));
            throw new RuntimeException("Unknown parameters provided: $unknown");
        }
        return $builder->build();
    }

    /**
     * Get the default handler
     *
     * @param array $multiParams
     * @param array $singleParams
     * @throws \RuntimeException
     */
    public static function defaultHandler(array $multiParams = [], array $singleParams = []): callable
    {
        $future = null;
        if (extension_loaded('curl')) {
            $config = array_merge([ 'mh' => curl_multi_init() ], $multiParams);
            if (function_exists('curl_reset')) {
                $default = new CurlHandler($singleParams);
                $future = new CurlMultiHandler($config);
            } else {
                $default = new CurlMultiHandler($config);
            }
        } else {
            throw new \RuntimeException('OpenSearch-PHP requires cURL, or a custom HTTP handler.');
        }

        return $future ? Middleware::wrapFuture($default, $future) : $default;
    }

    /**
     * Get the multi handler for async (CurlMultiHandler)
     *
     * @throws \RuntimeException
     */
    public static function multiHandler(array $params = []): CurlMultiHandler
    {
        if (function_exists('curl_multi_init')) {
            return new CurlMultiHandler(array_merge([ 'mh' => curl_multi_init() ], $params));
        }

        throw new \RuntimeException('CurlMulti handler requires cURL.');
    }

    /**
     * Get the handler instance (CurlHandler)
     *
     * @throws \RuntimeException
     */
    public static function singleHandler(): CurlHandler
    {
        if (function_exists('curl_reset')) {
            return new CurlHandler();
        }

        throw new \RuntimeException('CurlSingle handler requires cURL.');
    }

    /**
     * Set connection Factory
     *
     * @param ConnectionFactoryInterface $connectionFactory
     */
    public function setConnectionFactory(ConnectionFactoryInterface $connectionFactory): ClientBuilder
    {
        $this->connectionFactory = $connectionFactory;

        return $this;
    }

    /**
     * Set the connection pool (default is StaticNoPingConnectionPool)
     *
     * @param  AbstractConnectionPool|string $connectionPool
     * @param array $args
     * @throws \InvalidArgumentException
     */
    public function setConnectionPool($connectionPool, array $args = []): ClientBuilder
    {
        if (is_string($connectionPool)) {
            $this->connectionPool = $connectionPool;
            $this->connectionPoolArgs = $args;
        } elseif (is_object($connectionPool)) {
            $this->connectionPool = $connectionPool;
        } else {
            throw new InvalidArgumentException("Serializer must be a class path or instantiated object extending AbstractConnectionPool");
        }

        return $this;
    }

    /**
     * Set the endpoint
     *
     * @param callable $endpoint
     *
     * @deprecated in 2.4.0 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::setEndpointFactory() instead.
     */
    public function setEndpoint(callable $endpoint): ClientBuilder
    {
        @trigger_error(__METHOD__ . '() is deprecated in 2.4.0 and will be removed in 3.0.0. Use \OpenSearch\ClientBuilder::setEndpointFactory() instead.', E_USER_DEPRECATED);
        $this->endpointFactory = new LegacyEndpointFactory($endpoint);

        return $this;
    }

    public function setEndpointFactory(EndpointFactoryInterface $endpointFactory): ClientBuilder
    {
        $this->endpointFactory = $endpointFactory;
        return $this;
    }

    /**
     * Register namespace
     *
     * @param NamespaceBuilderInterface $namespaceBuilder
     */
    public function registerNamespace(NamespaceBuilderInterface $namespaceBuilder): ClientBuilder
    {
        $this->registeredNamespacesBuilders[] = $namespaceBuilder;

        return $this;
    }

    /**
     * Set the transport
     *
     * @param Transport $transport
     */
    public function setTransport(Transport $transport): ClientBuilder
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Set the HTTP handler (cURL is default)
     *
     * @param  mixed $handler
     */
    public function setHandler($handler): ClientBuilder
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Set the PSR-3 Logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): ClientBuilder
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Set the PSR-3 tracer
     *
     * @param LoggerInterface $tracer
     */
    public function setTracer(LoggerInterface $tracer): ClientBuilder
    {
        $this->tracer = $tracer;

        return $this;
    }

    /**
     * Set the serializer
     *
     * @param \OpenSearch\Serializers\SerializerInterface|string $serializer
     */
    public function setSerializer($serializer): ClientBuilder
    {
        $this->parseStringOrObject($serializer, $this->serializer, 'SerializerInterface');

        return $this;
    }

    /**
     * Set the hosts (nodes)
     *
     * @param array $hosts
     */
    public function setHosts(array $hosts): ClientBuilder
    {
        $this->hosts = $hosts;

        return $this;
    }

    /**
     * Set Basic access authentication
     *
     * @see https://en.wikipedia.org/wiki/Basic_access_authentication
     * @param string $username
     * @param string $password
     *
     * @throws AuthenticationConfigException
     */
    public function setBasicAuthentication(string $username, string $password): ClientBuilder
    {
        $this->basicAuthentication = $username.':'.$password;

        return $this;
    }

    /**
     * Set connection parameters
     *
     * @param array $params
     */
    public function setConnectionParams(array $params): ClientBuilder
    {
        $this->connectionParams = $params;

        return $this;
    }

    /**
     * Set number or retries (default is equal to number of nodes)
     *
     * @param int $retries
     */
    public function setRetries(int $retries): ClientBuilder
    {
        $this->retries = $retries;

        return $this;
    }

    /**
     * Set the selector algorithm
     *
     * @param \OpenSearch\ConnectionPool\Selectors\SelectorInterface|string $selector
     */
    public function setSelector($selector): ClientBuilder
    {
        $this->parseStringOrObject($selector, $this->selector, 'SelectorInterface');

        return $this;
    }

    /**
     * Set the credential provider for SigV4 request signing. The value provider should be a
     * callable object that will return
     *
     * @param callable|bool|array|CredentialsInterface|null $credentialProvider
     */
    public function setSigV4CredentialProvider($credentialProvider): ClientBuilder
    {
        if ($credentialProvider !== null && $credentialProvider !== false) {
            $this->sigV4CredentialProvider = $this->normalizeCredentialProvider($credentialProvider);
        }

        return $this;
    }

    /**
     * Set the region for SigV4 signing.
     *
     * @param string|null $region
     */
    public function setSigV4Region($region): ClientBuilder
    {
        $this->sigV4Region = $region;

        return $this;
    }

    /**
     * Set the service for SigV4 signing.
     *
     * @param string|null $service
     */
    public function setSigV4Service($service): ClientBuilder
    {
        $this->sigV4Service = $service;

        return $this;
    }

    /**
     * Set sniff on start
     *
     * @param bool $sniffOnStart enable or disable sniff on start
     */

    public function setSniffOnStart(bool $sniffOnStart): ClientBuilder
    {
        $this->sniffOnStart = $sniffOnStart;

        return $this;
    }

    /**
     * Set SSL certificate
     *
     * @param string $cert The name of a file containing a PEM formatted certificate.
     * @param string $password if the certificate requires a password
     */
    public function setSSLCert(string $cert, ?string $password = null): ClientBuilder
    {
        $this->sslCert = [$cert, $password];

        return $this;
    }

    /**
     * Set SSL key
     *
     * @param string $key The name of a file containing a private SSL key
     * @param string $password if the private key requires a password
     */
    public function setSSLKey(string $key, ?string $password = null): ClientBuilder
    {
        $this->sslKey = [$key, $password];

        return $this;
    }

    /**
     * Set SSL verification
     *
     * @param bool|string $value
     */
    public function setSSLVerification($value = true): ClientBuilder
    {
        $this->sslVerification = $value;

        return $this;
    }

    /**
     * Include the port in Host header
     *
     * @see https://github.com/elastic/elasticsearch-php/issues/993
     */
    public function includePortInHostHeader(bool $enable): ClientBuilder
    {
        $this->includePortInHostHeader = $enable;

        return $this;
    }

    /**
     * Build and returns the Client object
     */
    public function build(): Client
    {
        $this->buildLoggers();

        if (is_null($this->handler)) {
            $this->handler = ClientBuilder::defaultHandler();
        }

        if (!is_null($this->sigV4CredentialProvider)) {
            if (is_null($this->sigV4Region)) {
                throw new RuntimeException("A region must be supplied for SigV4 request signing.");
            }

            if (is_null($this->sigV4Service)) {
                $this->setSigV4Service("es");
            }

            $this->handler = new SigV4Handler($this->sigV4Region, $this->sigV4Service, $this->sigV4CredentialProvider, $this->handler);
        }

        $sslOptions = null;
        if (isset($this->sslKey)) {
            $sslOptions['ssl_key'] = $this->sslKey;
        }
        if (isset($this->sslCert)) {
            $sslOptions['cert'] = $this->sslCert;
        }
        if (isset($this->sslVerification)) {
            $sslOptions['verify'] = $this->sslVerification;
        }

        if (!is_null($sslOptions)) {
            $sslHandler = function (callable $handler, array $sslOptions) {
                return function (array $request) use ($handler, $sslOptions) {
                    // Add our custom headers
                    foreach ($sslOptions as $key => $value) {
                        $request['client'][$key] = $value;
                    }

                    // Send the request using the handler and return the response.
                    return $handler($request);
                };
            };
            $this->handler = $sslHandler($this->handler, $sslOptions);
        }

        if (is_null($this->serializer)) {
            $this->serializer = new SmartSerializer();
        } elseif (is_string($this->serializer)) {
            $this->serializer = new $this->serializer();
        }

        $this->connectionParams['client']['port_in_header'] = $this->includePortInHostHeader;

        if (! is_null($this->basicAuthentication)) {
            if (isset($this->connectionParams['client']['curl']) === false) {
                $this->connectionParams['client']['curl'] = [];
            }

            $this->connectionParams['client']['curl'] += [
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD  => $this->basicAuthentication
            ];
        }

        if (is_null($this->connectionFactory)) {
            // Make sure we are setting Content-Type and Accept (unless the user has explicitly
            // overridden it
            if (! isset($this->connectionParams['client']['headers'])) {
                $this->connectionParams['client']['headers'] = [];
            }
            if (! isset($this->connectionParams['client']['headers']['Content-Type'])) {
                $this->connectionParams['client']['headers']['Content-Type'] = ['application/json'];
            }
            if (! isset($this->connectionParams['client']['headers']['Accept'])) {
                $this->connectionParams['client']['headers']['Accept'] = ['application/json'];
            }

            $this->connectionFactory = new ConnectionFactory($this->handler, $this->connectionParams, $this->serializer, $this->logger, $this->tracer);
        }

        if (is_null($this->hosts)) {
            $this->hosts = $this->getDefaultHost();
        }

        if (is_null($this->selector)) {
            $this->selector = new RoundRobinSelector();
        } elseif (is_string($this->selector)) {
            $this->selector = new $this->selector();
        }

        $this->buildTransport();

        if (is_null($this->endpointFactory)) {
            $this->endpointFactory = new EndpointFactory($this->serializer);
        }

        $registeredNamespaces = [];
        foreach ($this->registeredNamespacesBuilders as $builder) {
            /**
             * @var NamespaceBuilderInterface $builder
             */
            $registeredNamespaces[$builder->getName()] = $builder->getObject($this->transport, $this->serializer);
        }

        return $this->instantiate($this->transport, $this->endpointFactory, $registeredNamespaces);
    }

    protected function instantiate(Transport $transport, EndpointFactoryInterface $endpointFactory, array $registeredNamespaces): Client
    {
        return new Client($transport, $endpointFactory, $registeredNamespaces);
    }

    private function buildLoggers(): void
    {
        if (is_null($this->logger)) {
            $this->logger = new NullLogger();
        }

        if (is_null($this->tracer)) {
            $this->tracer = new NullLogger();
        }
    }

    private function buildTransport(): void
    {
        $connections = $this->buildConnectionsFromHosts($this->hosts);

        if (is_string($this->connectionPool)) {
            $this->connectionPool = new $this->connectionPool(
                $connections,
                $this->selector,
                $this->connectionFactory,
                $this->connectionPoolArgs
            );
        }

        if (is_null($this->retries)) {
            $this->retries = count($connections);
        }

        if (is_null($this->transport)) {
            $this->transport = new Transport($this->retries, $this->connectionPool, $this->logger, $this->sniffOnStart);
        }
    }

    private function parseStringOrObject($arg, &$destination, $interface): void
    {
        if (is_string($arg)) {
            $destination = new $arg();
        } elseif (is_object($arg)) {
            $destination = $arg;
        } else {
            throw new InvalidArgumentException("Serializer must be a class path or instantiated object implementing $interface");
        }
    }

    private function getDefaultHost(): array
    {
        return ['localhost:9200'];
    }

    /**
     * @return ConnectionInterface[]
     * @throws RuntimeException
     */
    private function buildConnectionsFromHosts(array $hosts): array
    {
        $connections = [];
        foreach ($hosts as $host) {
            if (is_string($host)) {
                $host = $this->prependMissingScheme($host);
                $host = $this->extractURIParts($host);
            } elseif (is_array($host)) {
                $host = $this->normalizeExtendedHost($host);
            } else {
                $this->logger->error("Could not parse host: ".print_r($host, true));
                throw new RuntimeException("Could not parse host: ".print_r($host, true));
            }

            $connections[] = $this->connectionFactory->create($host);
        }

        return $connections;
    }

    /**
     * @throws RuntimeException
     */
    private function normalizeExtendedHost(array $host): array
    {
        if (isset($host['host']) === false) {
            $this->logger->error("Required 'host' was not defined in extended format: ".print_r($host, true));
            throw new RuntimeException("Required 'host' was not defined in extended format: ".print_r($host, true));
        }

        if (isset($host['scheme']) === false) {
            $host['scheme'] = 'http';
        }
        if (isset($host['port']) === false) {
            $host['port'] = 9200;
        }
        return $host;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function extractURIParts(string $host): array
    {
        $parts = parse_url($host);

        if ($parts === false) {
            throw new InvalidArgumentException(sprintf('Could not parse URI: "%s"', $host));
        }

        if (isset($parts['port']) !== true) {
            $parts['port'] = 9200;
        }

        return $parts;
    }

    private function prependMissingScheme(string $host): string
    {
        if (!preg_match("/^https?:\/\//", $host)) {
            $host = 'http://' . $host;
        }

        return $host;
    }

    private function normalizeCredentialProvider($provider): ?callable
    {
        if ($provider === null || $provider === false) {
            return null;
        }

        if (is_callable($provider)) {
            return $provider;
        }

        SigV4Handler::assertDependenciesInstalled();

        if ($provider === true) {
            return CredentialProvider::defaultProvider();
        }

        if ($provider instanceof CredentialsInterface) {
            return CredentialProvider::fromCredentials($provider);
        } elseif (is_array($provider) && isset($provider['key']) && isset($provider['secret'])) {
            return CredentialProvider::fromCredentials(
                new Credentials(
                    $provider['key'],
                    $provider['secret'],
                    isset($provider['token']) ? $provider['token'] : null,
                    isset($provider['expires']) ? $provider['expires'] : null
                )
            );
        }

        throw new InvalidArgumentException('Credentials must be an instance of Aws\Credentials\CredentialsInterface, an'
            . ' associative array that contains "key", "secret", and an optional "token" key-value pairs, a credentials'
            . ' provider function, or true.');
    }
}
