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

namespace OpenSearch\Util;

use Exception;

class NamespaceEndpoint
{
    public const NAMESPACE_CLASS_TEMPLATE        = __DIR__ . '/template/namespace-class';
    public const ENDPOINT_FUNCTION_TEMPLATE      = __DIR__ . '/template/endpoint-function';
    public const ENDPOINT_FUNCTION_BOOL_TEMPLATE = __DIR__ . '/template/endpoint-function-bool';
    public const EXTRACT_ARG_TEMPLATE            = __DIR__ . '/template/extract-arg';
    public const SET_PARAM_TEMPLATE              = __DIR__ . '/template/setparam';

    protected $name;
    protected $endpoints = [];
    protected $endpointNames = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function renderClass(): string
    {
        if (empty($this->endpoints)) {
            throw new Exception("No endpoints has been added. I cannot render the class");
        }
        $class = file_get_contents(static::NAMESPACE_CLASS_TEMPLATE);
        $namespaceName = $this->getNamespaceName(). 'Namespace';
        $class = str_replace(':namespace', $namespaceName, $class);

        # Add license header
        $currentDir = dirname(__FILE__);
        $baseDir = dirname($currentDir);
        $filePath = $baseDir . "/src/OpenSearch/Namespaces/$namespaceName.php";

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            if (strpos($content, 'Copyright OpenSearch') !== false) {
                $pattern = '/\/\*\*.*?\*\//s';
                if (preg_match($pattern, $content, $matches)) {
                    $class = str_replace('declare(strict_types=1);', 'declare(strict_types=1);' . PHP_EOL . PHP_EOL . $matches[0], $class);

                }
            }
        }

        $endpoints = '';
        foreach ($this->endpoints as $endpoint) {
            $endpointName = $this->getEndpointName($endpoint->name);
            $proxyFilePath = __DIR__ . '/EndpointProxies/' . $this->name . '/' . $endpointName . 'Proxy.php';
            if (!file_exists($proxyFilePath)) {
                $endpoints .= $this->renderEndpoint($endpoint);
            }
        }

        $proxyFolder = __DIR__ . '/EndpointProxies/' . $this->name;
        if (is_dir($proxyFolder)) {
            foreach (glob($proxyFolder . '/*Proxy.php') as $file) {
                $endpoints .= require $file;
            }
        }
        $class = str_replace(':endpoints', $endpoints, $class);

        return $class;
    }

    public function addEndpoint(Endpoint $endpoint): NamespaceEndpoint
    {
        if (in_array($endpoint->name, $this->endpointNames)) {
            throw new Exception(sprintf(
                "The endpoint %s has been already added",
                $endpoint->namespace
            ));
        }
        $this->endpoints[] = $endpoint;
        $this->endpointNames[] = $endpoint->name;

        return $this;
    }

    protected function renderEndpoint(Endpoint $endpoint): string
    {
        $code = file_get_contents(
            $endpoint->getMethod() === ['HEAD']
            ? self::ENDPOINT_FUNCTION_BOOL_TEMPLATE
            : self::ENDPOINT_FUNCTION_TEMPLATE
        );

        $code = str_replace(':apidoc', $endpoint->renderDocParams(), $code);
        $code = str_replace(':endpoint', $this->getEndpointName($endpoint->name), $code);

        $extract = '';
        $setParams = '';
        foreach ($endpoint->getParts() as $part => $value) {
            $extract .= str_replace(':part', $part, file_get_contents(self::EXTRACT_ARG_TEMPLATE));

            $param = str_replace(':param', $part, file_get_contents(self::SET_PARAM_TEMPLATE));

            $setParams .= str_replace(':Param', $this->normalizeName($part), $param);
        }
        if (!$endpoint->isBodyNull()) {
            $extract .= str_replace(':part', 'body', file_get_contents(self::EXTRACT_ARG_TEMPLATE));

            $param = str_replace(':param', 'body', file_get_contents(self::SET_PARAM_TEMPLATE));
            $setParams .= str_replace(':Param', 'Body', $param);
        }
        if (!empty($extract)) {
            $code = str_replace(':extract', $extract, $code);
        } else {
            $code = str_replace("\n" . ':extract', '', $code);
        }
        $code = str_replace(':setparam', $setParams, $code);

        if (empty($endpoint->namespace)) {
            $endpointClass = $endpoint->getClassName();
        } else {
            $endpointClass = NamespaceEndpoint::normalizeName($endpoint->namespace) . '\\' . $endpoint->getClassName();
        }
        return str_replace(':EndpointClass', $endpointClass, $code);
    }

    public static function normalizeName(string $name): string
    {
        return str_replace('_', '', ucwords($name, '_'));
    }

    public function getNamespaceName(): string
    {
        return $this->normalizeName($this->name);
    }

    protected function getEndpointName(string $name): string
    {
        return preg_replace_callback(
            '/_(.?)/',
            function ($matches) {
                return strtoupper($matches[1]);
            },
            $name
        );
    }
}
