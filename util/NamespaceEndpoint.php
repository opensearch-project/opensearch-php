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
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class NamespaceEndpoint
{
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
        $twig = $this->getTwig();
        $namespaceName = $this->getNamespaceName() . 'Namespace';

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

        $class = $twig->render('namespace-class.php.twig', [
            'namespace' => $namespaceName,
            'endpoints' => $endpoints,
        ]);

        // Add license header if existing file has one
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
        $twig = $this->getTwig();
        $templateName = $endpoint->getMethod() === ['HEAD']
            ? 'endpoint-function-bool.twig'
            : 'endpoint-function.twig';

        $extract = '';
        $setParams = '';
        foreach ($endpoint->getParts() as $part => $value) {
            $extract .= $twig->render('extract-arg.twig', ['part' => $part]);
            $setParams .= $twig->render('setparam.twig', [
                'param' => $part,
                'Param' => $this->normalizeName($part),
            ]);
        }
        if (!$endpoint->isBodyNull()) {
            $extract .= $twig->render('extract-arg.twig', ['part' => 'body']);
            $setParams .= $twig->render('setparam.twig', [
                'param' => 'body',
                'Param' => 'Body',
            ]);
        }

        if (empty($endpoint->namespace)) {
            $endpointClass = $endpoint->getClassName();
        } else {
            $endpointClass = NamespaceEndpoint::normalizeName($endpoint->namespace) . '\\' . $endpoint->getClassName();
        }
        $fullClass = '\\OpenSearch\\Endpoints\\' . $endpointClass;

        return $twig->render($templateName, [
            'apidoc' => $endpoint->renderDocParams(),
            'endpoint' => $this->getEndpointName($endpoint->name),
            'extract' => $extract,
            'setparam' => $setParams,
            'EndpointClass' => $fullClass,
        ]);
    }

    protected function getTwig(): Environment
    {
        $loader = new FilesystemLoader(__DIR__ . '/template');
        return new Environment($loader, ['autoescape' => false]);
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
