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
use JsonException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Endpoint
{
    public const PHP_RESERVED_WORDS = [
        'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch',
        'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do',
        'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach',
        'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final',
        'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements',
        'include', 'include_once', 'instanceof', 'insteadof', 'interface',
        'isset', 'list', 'namespace', 'new', 'or', 'print', 'private',
        'protected', 'public', 'require', 'require_once', 'return', 'static',
        'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor'
    ];
    // this is for backward compatibility
    public const BC_CLASS_NAME = [
        'Cat\Nodeattrs'      => 'NodeAttrs',
        'Indices\Forcemerge' => 'ForceMerge',
        'Mtermvectors'       => 'MTermVectors',
        'Termvectors'        => 'TermVectors'
    ];

    public $namespace;
    public $name;
    public $apiName;
    protected $content;
    protected $parts = [];
    protected $requiredParts = [];
    protected $useNamespace = [];
    private $addedPartInDoc = [];
    private $properties = [];

    public function __construct(
        string $fileName,
        string $content,
    ) {
        $this->apiName = basename($fileName, '.json');
        $parts = explode('.', $this->apiName);
        if (count($parts) === 1) {
            $this->namespace = '';
            $this->name = $parts[0];
        } elseif (count($parts) === 2) {
            $this->namespace = $parts[0];
            $this->name = $parts[1];
        }
        try {
            $this->content = json_decode(
                $content,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new Exception(sprintf(
                "The content of the endpoint is not JSON: %s\n",
                $e->getMessage()
            ));
        }
        $this->content = $this->content[$this->apiName];

        $this->parts = $this->getPartsFromContent($this->content);
        $this->requiredParts = $this->getRequiredParts($this->content, $this->namespace);
    }

    public function getParts(): array
    {
        return $this->parts;
    }

    private function getPartsFromContent(array $content): array
    {
        $parts = [];
        foreach ($content['url']['paths'] as $url) {
            if (isset($url['parts'])) {
                $parts = array_merge($parts, $url['parts']);
            }
        }
        return $parts;
    }

    private function getRequiredParts(array $content, string $namespace): array
    {
        $required = [];
        // Get the list of required parts
        foreach ($content['url']['paths'] as $path) {
            $required[] = isset($path['parts']) ? array_keys($path['parts']) : [];
        }
        if (count($required) > 1) {
            return call_user_func_array('array_intersect', $required);
        }
        if (empty($namespace) && !empty($required)) {
            return $required[0];
        }
        return $required;
    }

    public function getDocUrl(): string
    {
        return $this->content['documentation']['url'] ?? '';
    }

    public function renderClass(): string
    {
        $twig = $this->getTwig();
        $isBulk = isset($this->content['body']['serialize']) && $this->content['body']['serialize'] === 'bulk';
        $templateName = $isBulk ? 'endpoint-bulk-class.php.twig' : 'endpoint-class.php.twig';

        $namespace = $this->namespace === ''
            ? $this->normalizeName($this->namespace)
            : '\\' . $this->normalizeName($this->namespace);

        $action = $this->getMethod();
        if ($action === ['POST', 'PUT'] && $this->getClassName() !== 'Bulk') {
            $method = "'PUT'";
        } elseif (!empty($this->content['body']) && ($action === ['GET', 'POST'] || $action === ['POST', 'GET'])) {
            $method = 'isset($this->body) ? \'POST\' : \'GET\'';
        } elseif ($this->getClassName() == "Refresh" || $this->getClassName() == "Flush") {
            $method = "'POST'";
        } else {
            $method = sprintf("'%s'", reset($action));
        }

        $parts = '';
        if (!empty($this->content['body'])) {
            if ($isBulk) {
                $parts .= $this->getSetBulkBody();
            } else {
                $parts .= $this->getSetPart('body');
            }
        }
        foreach ($this->parts as $part => $value) {
            if (in_array($part, ['index', 'id'])) {
                continue;
            }
            if (isset($value['type']) && $value['type'] === 'array') {
                $parts .= $this->getSetPartList($part);
            } else {
                $parts .= $this->getSetPart($part);
            }
        }

        // extractUrl() calls addNamespace(), so it must run before getNamespaces()
        $uri = trim($this->extractUrl($this->content['url']['paths']));

        $class = $twig->render($templateName, [
            'namespace' => $namespace,
            'use_namespace' => $this->getNamespaces(),
            'deprecation_message' => $this->content['deprecation_message'] ?? null,
            'endpoint' => $this->getClassName(),
            'properties' => $this->getProperties(),
            'uri' => $uri,
            'params' => $this->extractParameters(),
            'method' => $method,
            'set_parts' => $parts,
            'has_master_timeout' => isset($this->content['params']['master_timeout']),
        ]);

        // Add license header if existing file has one
        $currentDir = dirname(__FILE__);
        $baseDir = dirname($currentDir);
        $EndpointName = $this->getClassName();

        if (!empty($this->namespace)) {
            $namespace_dir = str_replace('_', '', ucwords($this->namespace, '_'));
            $filePath = $baseDir . "/src/OpenSearch/Endpoints/$namespace_dir/$EndpointName.php";
        } else {
            $filePath = $baseDir . "/src/OpenSearch/Endpoints/$EndpointName.php";
        }

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

    public function getMethod(): array
    {
        $methods = $this->content['url']['paths'][0]['methods'];
        foreach ($this->content['url']['paths'] as $path) {
            $methods = array_intersect($methods, $path['methods']);
        }
        return $methods;
    }

    private function extractParameters(): string
    {
        if (!isset($this->content['params'])) {
            return '';
        }
        $tab12 = str_repeat(' ', 12);
        $tab8 = str_repeat(' ', 8);
        $result = '';
        foreach (array_keys($this->content['params']) as $param) {
            $result .=  "'$param',\n" . $tab12;
        }
        return "\n". $tab12 . rtrim(trim($result), ',') . "\n". $tab8;
    }

    private function getDeprecatedMessage(string $part): string
    {
        foreach ($this->content['url']['paths'] as $path) {
            if (isset($path['parts'][$part]) && isset($path['parts'][$part]['deprecated']) &&
                $path['parts'][$part]['deprecated']) {
                return $path['deprecated']['description'] ?? '';
            }
        }
        return '';
    }

    private function extractUrl(array $paths): string
    {
        $twig = $this->getTwig();
        $checkPart = '';
        $params = '';
        $deprecated = '';

        $tab8 = str_repeat(' ', 8);
        $tab12 = str_repeat(' ', 12);

        if (!empty($this->parts)) {
            foreach ($this->parts as $part => $value) {
                if (in_array($part, $this->requiredParts)) {
                    $checkPart .= $twig->render('required-part.twig', [
                        'part' => $part,
                        'endpoint' => $this->name,
                    ]);
                    $this->addNamespace('OpenSearch\Exception\RuntimeException');
                } else {
                    $params .= sprintf("%s\$%s = \$this->%s ? rawurlencode(\$this->%s) : null;", $tab8, $part, $part, $part);
                }
                if (isset($value['deprecated']) && $value['deprecated']) {
                    $deprecated .= $twig->render('deprecated.twig', [
                        'part' => $part,
                        'msg' => $this->getDeprecatedMessage($part),
                    ]);
                }
            }
        }
        $else = '';
        $urls = '';
        // Extract the paths to manage (removing deprecated path, duplicate, etc)
        $pathsToManage = $this->extractPaths($paths);

        $lastUrlReturn = false;
        foreach ($pathsToManage as $path) {
            $parts = $this->getPartsFromUrl($path);
            if (empty($parts)) {
                $else = sprintf("\n%sreturn \"%s\";", $tab8, $path);
                $lastUrlReturn = true;
                continue;
            }
            $check = '';
            if (!in_array($parts[0], $this->requiredParts)) {
                $check = sprintf("isset(\$%s)", $parts[0]);
            }
            $url = str_replace('{' . $parts[0] .'}', '$' . $parts[0], $path);
            for ($i = 1; $i < count($parts); $i++) {
                $url = str_replace('{' . $parts[$i] .'}', '$' . $parts[$i], $url);
                if (in_array($parts[$i], $this->requiredParts)) {
                    continue;
                }
                $check .= sprintf("%sisset(\$%s)", empty($check) ? '' : ' && ', $parts[$i]);
            }
            // Fix for missing / at the beginning of URL
            // @see https://github.com/elastic/elasticsearch-php/pull/970
            if ($url[0] !== '/') {
                $url = '/' . $url;
            }
            if (empty($check)) {
                $urls .= sprintf("\n%sreturn \"%s\";", $tab8, $url);
                $lastUrlReturn = true;
            } else {
                $urls .= sprintf("\n%sif (%s) {\n%sreturn \"%s\";\n%s}", $tab8, $check, $tab12, $url, $tab8);
            }
        }
        if (!$lastUrlReturn) {
            $urls .= sprintf(
                "\n%sthrow new RuntimeException('Missing parameter for the endpoint %s');",
                $tab8,
                $this->apiName
            );
            $this->addNamespace('OpenSearch\Exception\RuntimeException');
        }
        return $checkPart . $params . $deprecated . $urls . $else;
    }

    private function removePathWithSameParts(array $paths): array
    {
        $urls = [];
        $parsed = [];
        foreach ($paths as $path) {
            if (!isset($path['parts'])) {
                $urls[] = $path['path'];
                continue;
            }
            $parts = array_keys($path['parts']);
            $exist = false;
            foreach ($parsed as $parse) {
                if ($parts == $parse) {
                    $exist = true;
                    break;
                }
            }
            if (!$exist) {
                $urls[] = $path['path'];
                $parsed[] = $parts;
            };
        }
        return $urls;
    }

    private function extractPaths(array $paths): array
    {
        $urls = $this->removePathWithSameParts($paths);
        // Order the url based on descendant length
        usort($urls, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        return $urls;
    }

    private function getPartsFromUrl(string $url): array
    {
        preg_match_all('#\{([a-z_]+)\}#', $url, $match);
        return $match[1];
    }

    private function addNamespace(string $namespace): void
    {
        $this->useNamespace[$namespace] = sprintf("use %s;", $namespace);
    }

    private function getNamespaces(): string
    {
        if (empty($this->useNamespace)) {
            return '';
        }
        return "\n" . implode("\n", $this->useNamespace);
    }

    private function getSetPartList(string $param): string
    {
        $twig = $this->getTwig();
        $this->addProperty($param);
        return $twig->render('set-part-list.twig', [
            'endpoint' => $this->getClassName(),
            'part' => $param,
            'Part' => $this->normalizeName($param),
        ]);
    }

    private function getSetPart(string $param): string
    {
        $twig = $this->getTwig();
        $this->addProperty($param);
        return $twig->render('set-part.twig', [
            'endpoint' => $this->getClassName(),
            'part' => $param,
            'Part' => $this->normalizeName($param),
        ]);
    }

    private function getSetBulkBody(): string
    {
        $twig = $this->getTwig();
        $this->addNamespace('OpenSearch\Common\Exceptions\InvalidArgumentException');
        return $twig->render('set-bulk-body.twig', [
            'endpoint' => $this->getClassName(),
        ]);
    }

    private function getTwig(): Environment
    {
        $loader = new FilesystemLoader(__DIR__ . '/template');
        return new Environment($loader, ['autoescape' => false]);
    }

    protected function addProperty(string $name)
    {
        if (!in_array($name, ['body', 'index', 'id'])) {
            $this->properties[$name] = sprintf("    protected \$%s;", $name);
        }
    }

    protected function getProperties(): string
    {
        if (empty($this->properties)) {
            return '';
        }
        return implode("\n", $this->properties) . "\n";
    }

    protected function normalizeName(string $name): string
    {
        return str_replace('_', '', ucwords($name, '_'));
    }

    public function getClassName(): string
    {
        if (in_array(strtolower($this->name), static::PHP_RESERVED_WORDS)) {
            return $this->normalizeName($this->name . ucwords($this->namespace));
        }
        $normalizedName = $this->normalizeName($this->name);
        $normalizedFullName = empty($this->namespace) ? $normalizedName : ucwords($this->namespace) . '\\' . $normalizedName;

        return static::BC_CLASS_NAME[$normalizedFullName] ?? $normalizedName;
    }

    public function renderDocParams(): string
    {
        $space = $this->getMaxLengthBodyPartsParams();
        $result = "\n    /**\n";

        // Method description
        if (isset($this->content['documentation']['description'])) {
            $result .= sprintf(
                "     * %s\n",
                str_replace("\n", '', $this->content['documentation']['description'])
            );
            $result .= "     *\n";
        }

        // Input parameters
        $result .= $this->extractAllParamsDescription($space);

        // Return value
        if ($this->getMethod() === ['HEAD']) {
            $result .= "     * @return bool\n";
        } else {
            $result .= "     * @return array\n";
        }

        // Documentation URL
        if (isset($this->content['documentation']['url'])) {
            $result .= "     * @see {$this->content['documentation']['url']}\n";
        }

        // Stability note
        if ($this->content['stability'] !== 'stable') {
            switch ($this->content['stability']) {
                case 'experimental':
                    $note = 'This API is EXPERIMENTAL and may be changed or removed completely in a future release';
                    break;
                case 'beta':
                    $note = 'This API is BETA and may change in ways that are not backwards compatible';
                    break;
            }
            if (isset($note)) {
                $result .= sprintf("     *\n     * @note %s\n     *\n", $note);
            }
        }

        $result .= "     */";
        return $result;
    }

    private function extractAllParamsDescription(int $space): string
    {
        $shapeParts = [];
        $descLines  = [];
        $allParams  = [];

        // Merge parts, params, and body
        if (!empty($this->parts)) {
            foreach ($this->parts as $name => $values) {
                $allParams[$name] = $values + ['source' => 'parts'];
            }
        }
        if (!empty($this->content['params'])) {
            foreach ($this->content['params'] as $name => $values) {
                $allParams[$name] = $values + ['source' => 'params'];
            }
        }
        if (!empty($this->content['body'])) {
            $allParams['body'] = $this->content['body'] + ['source' => 'body'];
        }

        $anyRequired = false;

        foreach ($allParams as $name => $values) {
            if (in_array($name, $this->addedPartInDoc, true)) {
                continue;
            }

            $type = $this->mapTypeToPhpDoc($values['type'] ?? 'mixed');

            // Determine if required - note: for PHPStan compatibility with $params = [],
            // we mark all parameters as optional in the array shape but still document
            // which ones are required in the description
            $isRequired = $values['required'] ?? false;

            if ($name === 'id') {
                $isRequired = true; // id always required
            }

            // Always use optional marker for PHPStan compatibility
            $optional = '?';
            if ($isRequired) {
                $anyRequired = true;
            }

            // Quote keys with dots or other special characters for PHPStan compatibility
            $key = str_contains($name, '.') ? "'{$name}'" : $name;
            $shapeParts[] = sprintf('%s%s: %s', $key, $optional, $type);

            // Description
            $desc = $values['description'] ?? '';
            if ($isRequired) {
                $desc = ($desc ? $desc . ' ' : '') . '(Required)';
            }
            if (isset($values['default'])) {
                $default = $values['default'];
                if (is_bool($default)) {
                    $default = $default ? 'true' : 'false';
                } elseif (is_array($default)) {
                    $default = implode(', ', $default);
                }
                $desc .= ($desc ? ' ' : '') . "(Default: {$default})";
            }
            if (isset($values['options'])) {
                $desc .= ($desc ? ' ' : '') . '(Options: ' . implode(', ', $values['options']) . ')';
            }

            $descLines[] = sprintf("     * - %s: %s", $name, trim($desc));
            $this->addedPartInDoc[] = $name;
        }

        // Build docblock string
        if (empty($shapeParts)) {
            return '';
        }

        $result  = sprintf(
            "     * @param array{%s} \$params\n",
            implode(', ', $shapeParts)
        );
        foreach ($descLines as $line) {
            $result .= $line . "\n";
        }

        return $result;
    }

    private function mapTypeToPhpDoc(mixed $type): string
    {
        // If the type is an array (nested schema), treat as 'array'
        if (is_array($type)) {
            return 'array';
        }

        return match ($type) {
            'string'  => 'string',
            'boolean' => 'bool',
            'integer' => 'int',
            'number'  => 'int|float',
            'list'    => 'array',
            'object'  => 'array',
            'any'     => 'mixed',
            default   => 'mixed',
        };
    }

    private function getMaxLengthBodyPartsParams(): int
    {
        $max = isset($this->content['body']) ? 4 : 0;
        if (!empty($this->parts)) {
            foreach ($this->parts as $name => $value) {
                $len = strlen($name);
                if ($len > $max) {
                    $max = $len;
                }
            }
        }
        if (!empty($this->content['params'])) {
            foreach ($this->content['params'] as $name => $value) {
                $len = strlen($name);
                if ($len > $max) {
                    $max = $len;
                }
            }
        }
        return $max;
    }

    public function isBodyNull(): bool
    {
        return empty($this->content['body']);
    }
}
