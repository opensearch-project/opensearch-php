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

class ClientEndpoint extends NamespaceEndpoint
{
    protected $endpoints = [];
    protected $endpointNames = [];
    protected $namespace = [];

    public function __construct(array $namespace)
    {
        $this->namespace = $namespace;
    }

    public function renderClass(): string
    {
        if (empty($this->endpoints)) {
            throw new Exception("No endpoints has been added. I cannot render the class");
        }
        $twig = $this->getTwig();

        // use Namespace
        $useNamespace = '';

        // The following namespaces do not have OpenSearch API specifications
        // @todo Remove these deprecated namespaces in 3.0.0
        $deprecatedNamespaces = ['async_search', 'searchable_snapshots', 'ssl', 'data_frame_transform_deprecated', 'monitoring'];
        $this->namespace = array_unique(array_merge($this->namespace, $deprecatedNamespaces));
        sort($this->namespace);

        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $useNamespace .= sprintf("use OpenSearch\Namespaces\%sNamespace;\n", NamespaceEndpoint::normalizeName($name));
        }

        // new Namespace
        $newNamespace = '';
        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $template = in_array($name, $deprecatedNamespaces)
                ? 'new-namespace-deprecated.twig'
                : 'new-namespace.twig';
            $normNamespace = NamespaceEndpoint::normalizeName($name);
            $newNamespace .= $twig->render($template, [
                'namespace' => $normNamespace . 'Namespace',
                'name' => lcfirst($normNamespace),
            ]);
        }

        // Properties
        $properties = '';
        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $template = in_array($name, $deprecatedNamespaces)
                ? 'namespace-property-deprecated.twig'
                : 'namespace-property.twig';
            $normNamespace = NamespaceEndpoint::normalizeName($name);
            $properties .= $twig->render($template, [
                'namespace' => $normNamespace,
                'var_namespace' => lcfirst($normNamespace),
            ]) . "\n";
        }

        // Endpoints
        $endpoints = '';
        foreach ($this->endpoints as $endpoint) {
            $endpointName = $this->getEndpointName($endpoint->name);
            $proxyFilePath = __DIR__ . '/EndpointProxies/' . $this->name . '/' . $endpointName . 'Proxy.php';
            if (!file_exists($proxyFilePath)) {
                $endpoints .= $this->renderEndpoint($endpoint);
            }
        }
        $proxyFolder = __DIR__ . '/EndpointProxies/';
        if (is_dir($proxyFolder)) {
            $proxyFiles = glob($proxyFolder . '/*.php');
            foreach ($proxyFiles as $file) {
                $endpoints .= require $file;
            }
        }

        // Namespace functions
        $functions = '';
        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $template = in_array($name, $deprecatedNamespaces)
                ? 'client-namespace-function-deprecated.twig'
                : 'client-namespace-function.twig';
            $normNamespace = NamespaceEndpoint::normalizeName($name);
            $functions .= $twig->render($template, [
                'namespace' => $normNamespace . 'Namespace',
                'name' => lcfirst($normNamespace),
            ]);
        }

        return $twig->render('client-class.php.twig', [
            'use_namespaces' => $useNamespace,
            'namespace_properties' => $properties,
            'new_namespaces' => $newNamespace,
            'endpoints' => $endpoints,
            'functions' => $functions,
        ]);
    }
}
