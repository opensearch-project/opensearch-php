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
    public const CLIENT_CLASS_TEMPLATE   = __DIR__ . '/template/client-class';
    public const NEW_NAMESPACE_TEMPLATE  = __DIR__ . '/template/new-namespace';
    public const PROPERTY_CLASS_TEMPLATE = __DIR__ . '/template/namespace-property';
    public const NAMESPACE_FUNC_TEMPLATE = __DIR__ . '/template/client-namespace-function';

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
        $class = file_get_contents(self::CLIENT_CLASS_TEMPLATE);
        // use Namespace
        $useNamespace = '';

        // The following namespaces do not have OpenSearch API specifications
        $patchnamespaces = ['async_search', 'searchable_snapshots', 'ssl', 'sql', 'data_frame_transform_deprecated', 'monitoring'];
        $this->namespace = array_unique(array_merge($this->namespace, $patchnamespaces));
        sort($this->namespace);

        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $useNamespace .= sprintf("use OpenSearch\Namespaces\%sNamespace;\n", NamespaceEndpoint::normalizeName($name));
        }
        $class = str_replace(':use-namespaces', $useNamespace, $class);

        // new Namespace
        $newNamespace = '';
        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $normNamespace = NamespaceEndpoint::normalizeName($name);
            $newName = file_get_contents(self::NEW_NAMESPACE_TEMPLATE);
            $newName = str_replace(':namespace', $normNamespace . 'Namespace', $newName);
            $newName = str_replace(':name', lcfirst($normNamespace), $newName);
            $newNamespace .= $newName;
        }
        $class = str_replace(':new-namespaces', $newNamespace, $class);

        // Properties
        $properties = '';
        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $normNamespace = NamespaceEndpoint::normalizeName($name);
            $prop = file_get_contents(self::PROPERTY_CLASS_TEMPLATE);
            $prop = str_replace(':namespace', $normNamespace, $prop);
            $prop = str_replace(':var_namespace', lcfirst($normNamespace), $prop);
            $properties .= $prop . "\n";
        }
        $class = str_replace(':namespace_properties', $properties, $class);

        // Endpoints
        $endpoints = '';
        foreach ($this->endpoints as $endpoint) {
            $endpointName = $this->getEndpointName($endpoint->name);
            $proxyFilePath = __DIR__ . '/EndpointProxies/' . $this->name . '/' . $endpointName . 'Proxy.php';
            if (!file_exists($proxyFilePath)) {
                $endpoints .= $this->renderEndpoint($endpoint);
            }
        }
        $proxyFolder = __DIR__. '/EndpointProxies/';
        if (is_dir($proxyFolder)) {
            $proxyFiles = glob($proxyFolder . '/*.php');
            foreach ($proxyFiles as $file) {
                $endpoints .= require $file;
            }
        }
        $class = str_replace(':endpoints', $endpoints, $class);

        // Namespace functions
        $functions = '';
        foreach ($this->namespace as $name) {
            if (empty($name)) {
                continue;
            }
            $normNamespace = NamespaceEndpoint::normalizeName($name);
            $func = file_get_contents(self::NAMESPACE_FUNC_TEMPLATE);
            $func = str_replace(':namespace', $normNamespace . 'Namespace', $func);
            $func = str_replace(':name', lcfirst($normNamespace), $func);
            $functions .= $func;
        }
        $class = str_replace(':functions', $functions, $class);

        return $class;
    }
}
