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

use Elastic\Transport\TransportBuilder;
use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Namespaces\NamespaceBuilderInterface;
use OpenSearch\Serializers\SmartSerializer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionClass;

class ClientBuilder
{
    private $endpoint;

    private array $registeredNamespacesBuilders = [];

    private LoggerInterface $logger;

    private array $hosts = ['localhost:9200'];

    private ?int $retries = null;

    /**
     * @var null|array
     */
    private ?array $sslCert = null;

    private ?array $sslKey;

    private bool $sslVerification = true;

    private array $basicAuthentication = [];

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * Create an instance of ClientBuilder
     */
    public static function create(): ClientBuilder
    {
        return new self();
    }

    /**
     * Can supply second param to Client::__construct() when invoking manually or with dependency injection
     */
    public function getEndpoint(): callable
    {
        return $this->endpoint;
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
     * @throws \OpenSearch\Common\Exceptions\RuntimeException
     */
    public static function fromConfig(array $config, bool $quiet = false): Client
    {
        $builder = new self();
        foreach ($config as $key => $value) {
            $method = "set$key";
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
     * Set the endpoint
     *
     * @param callable $endpoint
     */
    public function setEndpoint(callable $endpoint): ClientBuilder
    {
        $this->endpoint = $endpoint;

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
     * Set the hosts (nodes)
     *
     * @param array $hosts
     */
    public function setHosts(array $hosts): ClientBuilder
    {
        $this->hosts = $hosts;
        return $this;
    }

    public function setBasicAuthentication(string $username, ?string $password = null): ClientBuilder
    {
        $this->basicAuthentication = array_filter([$username, $password]);
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
     * @return $this
     */
    public function setSSLVerification(bool $value = true): ClientBuilder
    {
        $this->sslVerification = $value;
        return $this;
    }

    /**
     * Build and returns the Client object
     */
    public function build(): Client
    {
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
        }

        $transport = $this->buildTransport();

        if (is_null($this->endpoint)) {

            $this->endpoint = function ($class) {
                $fullPath = '\\OpenSearch\\Endpoints\\' . $class;

                $reflection = new ReflectionClass($fullPath);
                $constructor = $reflection->getConstructor();

                if ($constructor && $constructor->getParameters()) {
                    return new $fullPath(new SmartSerializer());
                }

                return new $fullPath();
            };
        }

        $registeredNamespaces = [];
        foreach ($this->registeredNamespacesBuilders as $builder) {
            /**
             * @var NamespaceBuilderInterface $builder
             */
            $registeredNamespaces[$builder->getName()] = $builder->getObject($transport, new SmartSerializer());
        }

        return $this->instantiate($transport, $this->endpoint, $registeredNamespaces);
    }

    protected function instantiate(Transport $transport, callable $endpoint, array $registeredNamespaces): Client
    {
        return new Client($transport, $endpoint, $registeredNamespaces);
    }

    private function buildTransport(): Transport
    {
        $transport = TransportBuilder::create()
            ->setHosts($this->hosts)
            ->setLogger($this->logger)
            ->build();

        $transport->setUserAgent('opensearch-php', Client::VERSION);

        if ($this->basicAuthentication) {
            $transport->setUserInfo(...$this->basicAuthentication);
        }

        if ($this->retries) {
            $transport->setRetries($this->retries);
        }

        return new Transport($transport);
    }
}
