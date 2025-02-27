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
use ParseError;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use stdClass;

class YamlTests
{
    public const TEMPLATE_UNIT_TEST     = __DIR__ . '/template/test/unit-test-oss';
    public const TEMPLATE_UNIT_TEST_SKIPPED = __DIR__ . '/template/test/unit-test-skipped';
    public const TEMPLATE_FUNCTION_TEST     = __DIR__ . '/template/test/function-test';
    public const TEMPLATE_FUNCTION_SKIPPED  = __DIR__ . '/template/test/function-skipped';
    public const OPENSEARCH_GIT_URL      = 'https://github.com/opensearch-project/OpenSearch/tree/%s/rest-api-spec/src/main/resources/rest-api-spec/test/%s';

    public const SKIPPED_TEST = [
        'Cat\Nodeattrs\_10_BasicTest::TestCatNodesAttrsOutput' => 'Regexp error, it seems not compatible with PHP',
        'Cat\Shards\_10_BasicTest::TestCatShardsOutput' => 'Regexp error, it seems not compatible with PHP',
        'Search\Aggregation\_10_HistogramTest::HistogramProfiler' => "Error reading 'n' field from YAML",
        'Indices\GetAlias\_10_BasicTest::GetAliasAgainstClosedIndices' => 'Failed asserting that true is false'
    ];

    public const PHP_RESERVED_WORDS     = [
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

    private $tests = [];
    private $testOutput;
    private $testDir;

    public static $esVersion;
    public static $minorEsVersion;
    public static $testSuite;

    public function __construct(string $testDir, string $testOutput, string $esVersion, string $testSuite)
    {
        if (!is_dir($testDir)) {
            throw new Exception(sprintf(
                "The directory %s specified does not exist",
                $testDir
            ));
        }
        if (file_exists($testOutput)) {
            $this->removeDirectory($testOutput);
        }
        self::$testSuite = ucfirst($testSuite);

        $this->testOutput = sprintf("%s/%s", $testOutput, self::$testSuite);
        $this->testDir = $testDir;
        $this->tests = $this->getAllTests($testDir);

        self::$esVersion = $esVersion;
        list($major, $minor, $patch) = explode('.', self::$esVersion);
        self::$minorEsVersion = sprintf("%s.%s", $major, $minor);
    }

    private function getAllTests(string $dir): array
    {
        $it = new RecursiveDirectoryIterator($dir);
        $parsed = [];
        // Iterate over the Yaml test files
        foreach (new RecursiveIteratorIterator($it) as $file) {
            if ($file->getExtension() === 'yml') {
                $test = yaml_parse_file($file->getPathname(), -1, $ndocs, [
                    YAML_MAP_TAG => function ($value, $tag, $flags) {
                        return empty($value) ? new stdClass() : $value;
                    }
                ]);
                if (false === $test) {
                    throw new Exception(sprintf(
                        "YAML parse error file %s",
                        $file->getPathname()
                    ));
                }
                $parsed[$file->getPathname()] = $test;
            }
        }
        return $parsed;
    }

    public function build(): array
    {
        $numTest = 0;
        $numFile = 0;
        foreach ($this->tests as $testFile => $value) {
            $namespace = $this->extractTestNamespace($testFile);
            $testName = $this->extractTestName($testFile);
            $yamlFileName = substr($testFile, strlen($this->testDir) + 1);

            # Delete and create the output directory
            $testDirName = sprintf("%s/%s", $this->testOutput, str_replace('\\', '/', $namespace));
            if (!is_dir($testDirName) && !mkdir($testDirName, 0777, true) && !is_dir($testDirName)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $testDirName));
            }

            $functions = '';
            $setup = '';
            $teardown = '';
            $alreadyAssignedNames = [];
            $allSkipped = false;
            foreach ($value as $test) {
                if (!is_array($test)) {
                    continue;
                }
                foreach ($test as $name => $actions) {
                    switch ($name) {
                        case 'setup':
                            $setup = (string) new ActionTest($actions);
                            break;
                        case 'teardown':
                            $teardown = (string) new ActionTest($actions);
                            break;
                        default:
                            $functionName = $this->filterFunctionName(ucwords($name), $alreadyAssignedNames);
                            $alreadyAssignedNames[] = $functionName;

                            $skippedTest = sprintf("%s\\%s::%s", $namespace, $testName, $functionName);
                            $skippedAllTest = sprintf("%s\\%s::*", $namespace, $testName);
                            $skippedAllFiles = sprintf("%s\\*", $namespace);
                            $skip = self::SKIPPED_TEST;
                            if (isset($skip[$skippedAllFiles]) || isset($skip[$skippedAllTest])) {
                                $allSkipped = true;
                                $functions .= self::render(
                                    self::TEMPLATE_FUNCTION_SKIPPED,
                                    [
                                        ':name' => $functionName,
                                        ':skipped_msg'  => $skip[$skippedAllTest]
                                    ]
                                );
                            } elseif (isset($skip[$skippedTest])) {
                                $functions .= self::render(
                                    self::TEMPLATE_FUNCTION_SKIPPED,
                                    [
                                        ':name' => $functionName,
                                        ':skipped_msg'  => $skip[$skippedTest]
                                    ]
                                );
                            } else {
                                $functions .= self::render(
                                    self::TEMPLATE_FUNCTION_TEST,
                                    [
                                        ':name' => $functionName,
                                        ':test' => (string) new ActionTest($actions)
                                    ]
                                );
                            }
                            $numTest++;
                    }
                }
            }
            if ($allSkipped) {
                $test = self::render(
                    self::TEMPLATE_UNIT_TEST_SKIPPED,
                    [
                        ':namespace' => sprintf("OpenSearch\Tests\Yaml\%s\%s", self::$testSuite, $namespace),
                        ':test-name' => $testName,
                        ':tests'     => $functions,
                        ':yamlfile'  => sprintf(self::OPENSEARCH_GIT_URL, self::$minorEsVersion, $yamlFileName),
                        ':group'     => strtolower(self::$testSuite)
                    ]
                );
            } else {
                $test = self::render(
                    self::TEMPLATE_UNIT_TEST,
                    [
                        ':namespace' => sprintf("OpenSearch\Tests\Yaml\%s\%s", self::$testSuite, $namespace),
                        ':test-name' => $testName,
                        ':tests'     => $functions,
                        ':setup'     => $setup,
                        ':teardown'  => $teardown,
                        ':yamlfile'  => sprintf(self::OPENSEARCH_GIT_URL, self::$minorEsVersion, $yamlFileName),
                        ':group'     => strtolower(self::$testSuite)
                    ]
                );
            }
            file_put_contents($testDirName . '/' . $testName . '.php', $test);
            try {
                eval(substr($test, 5)); // remove <?php header
            } catch (ParseError $e) {
                throw new Exception(sprintf(
                    "The PHP code generate in %s not valid: %s",
                    $testDirName . '/' . $testName . '.php',
                    $e->getMessage()
                ));
            }
            $numFile++;
        }
        return [
            'tests' => $numTest,
            'files' => $numFile
        ];
    }

    private function extractTestNamespace(string $path)
    {
        $file = substr($path, strlen($this->testDir) + 1);
        $last = strrpos($file, '/', -1);

        $namespace = substr($file, 0, $last);
        $namespace = ucwords($namespace, '._/-');
        $namespace = str_replace(['.', '_', '/', '-'], ['\\', '', '\\', ''], ucwords($namespace, '.'));

        // Check if a PHP reserved word is present in the namespace
        $parts = explode('\\', $namespace);
        foreach ($parts as $part) {
            if (in_array(strtolower($part), self::PHP_RESERVED_WORDS)) {
                $namespace = str_replace($part, $part . '_', $namespace);
            }
        }
        return $namespace;
    }

    private function extractTestName(string $path): string
    {
        $file = substr($path, strlen($this->testDir) + 1);
        $last = strrpos($file, '/', -1);

        $testName = substr($file, $last + 1, -4);
        $testName = ucwords($testName, '_-');
        $testName = str_replace('-', '', $testName);

        return '_' . $testName . 'Test';
    }

    public static function render(string $fileName, array $params = []): string
    {
        if (!is_file($fileName)) {
            throw new Exception(sprintf(
                "The file %s is not valid",
                $fileName
            ));
        }
        $output = file_get_contents($fileName);
        foreach ($params as $name => $value) {
            if (is_array($value)) {
                $value = var_export($value, true);
            } elseif ($value instanceof stdClass) {
                $value = 'new \stdClass';
            } elseif (is_numeric($value)) {
                $value = (string) $value;
            }
            $output = str_replace($name, $value, $output);
        }
        return $output;
    }

    private function removeDirectory($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                $this->removeDirectory($file);
            } else {
                unlink($file);
            }
        }
        if (is_dir($directory)) {
            rmdir($directory);
        }
    }

    private function filterFunctionName(string $name, array $alreadyAssigned = []): string
    {
        $result = preg_replace("/[^a-zA-Z0-9_]/", "", $name);
        while (in_array($result, $alreadyAssigned)) {
            $result .= '_';
        }
        return $result;
    }
}
