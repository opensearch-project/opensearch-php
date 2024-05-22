<?php


declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */
use OpenSearch\Client;
use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Util\ClientEndpoint;
use OpenSearch\Util\Endpoint;
use OpenSearch\Util\NamespaceEndpoint;
use OpenSearch\Tests\Utility;
use Symfony\Component\Yaml\Yaml;

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/util/license_header.php';

$start = microtime(true);
printf("Generating endpoints for OpenSearch\n");

$success = true;

// Load the OpenAPI specification file
$url = "https://github.com/opensearch-project/opensearch-api-specification/releases/download/main/opensearch-openapi.yaml";
$yamlContent = file_get_contents($url);
$data = Yaml::parse($yamlContent);

$list_of_dicts = [];
foreach ($data["paths"] as $path => $pathDetails) {

    foreach ($pathDetails as $method => $methodDetails) {
        $methodDetails["path"] = $path;
        $methodDetails["method"] = $method;
        $list_of_dicts[] = $methodDetails;
    }
}


$outputDir = __DIR__ . "/output";
if (!file_exists($outputDir)) {
    mkdir($outputDir);
}

$endpointDir = "$outputDir/Endpoints/";
if (!file_exists($endpointDir)) {
    mkdir($endpointDir);
}

$countEndpoint = 0;
$namespaces = [];

$count = 0;
foreach ($list_of_dicts as $index => $endpoint) {

    if (array_key_exists("parameters", $endpoint)) {

        $params = [];
        $parts = [];
        // Iterate over the list of parameters and update them
        foreach ($endpoint["parameters"] as $param_ref) {
            $param_ref_value = substr($param_ref["$"."ref"], strrpos($param_ref["$"."ref"], '/') + 1);
            $param = $data["components"]["parameters"][$param_ref_value];
            if (isset($param["schema"]) && isset($param["schema"]["$"."ref"])) {
                $schema_path_ref = substr($param["schema"]["$"."ref"], strrpos($param["schema"]["$"."ref"], '/') + 1);
                $param["schema"] = $data["components"]["schemas"][$schema_path_ref];
                $params[] = $param;
            } else {
                $params[] = $param;
            }
        }
        var_dump($params);

        // Iterate over the list of updated parameters to separate "parts" from "params"
        $params_copy = $params;

        foreach ($params_copy as $key => $param) {
            if ($param["in"] === "path") {
                $parts[] = $param;
                unset($params[$key]);
            }
        }

        // Convert "params" and "parts" into the structure required for generator.
        $params_new = [];
        $parts_new = [];

        foreach ($params as $param) {
            $param_dict = [];

            if (isset($param['description'])) {
                $param_dict['description'] = str_replace("\n", "", $param['description']);
            }

            if (isset($param['schema']['type'])) {
                $param_dict['type'] = $param['schema']['type'];
            }

            if (isset($param['schema']['default'])) {
                $param_dict['default'] = $param['schema']['default'];
            }

            if (isset($param['schema']['enum'])) {
                $param_dict['type'] = 'enum';
                $param_dict['options'] = $param['schema']['enum'];
            }

            if (isset($param['deprecated'])) {
                $param_dict['deprecated'] = $param['deprecated'];
            }

            if (isset($param['x-deprecation-message'])) {
                $param_dict['deprecation_message'] = $param['x-deprecation-message'];
            }

            $params_new[$param['name']] = $param_dict;
        }

        if ($endpoint['x-operation-group'] !== 'nodes.hot_threads' && isset($params_new['type'])) {
            unset($params_new['type']);
        }
        if (!empty($params_new)) {
            $endpoint['params'] = $params_new;
        }

        foreach ($parts as $part) {
            $parts_dict = [];

            if (isset($part['schema']['type'])) {
                $parts_dict['type'] = $part['schema']['type'];
            } elseif (isset($part['schema']['oneOf'])) {
                foreach ($part['schema']['oneOf'] as $item) {
                    if (isset($item['type'])) {
                        $parts_dict['type'] = $item['type'];
                        break;
                    }
                }
            }

            if (isset($part['description'])) {
                $parts_dict['description'] = str_replace("\n", " ", $part['description']);
            }

            if (isset($part['schema']['x-enum-options'])) {
                $parts_dict['options'] = $part['schema']['x-enum-options'];
            }

            if (isset($part['deprecated'])) {
                $parts_dict['deprecated'] = $part['deprecated'];
            }

            $parts_new[$part['name']] = $parts_dict;
        }
        if (!empty($parts_new)) {
            $endpoint['parts'] = $parts_new;
        }
        $list_of_dicts[$index] = $endpoint;
    }
}
$files = [];
// Sort $list_of_dicts by the value of the "x-operation-group" key
usort($list_of_dicts, function ($a, $b) {
    return $a['x-operation-group'] <=> $b['x-operation-group'];
});

// Group $list_of_dicts by the value of the "x-operation-group" key
$grouped = [];
foreach ($list_of_dicts as $dict) {
    $grouped[$dict['x-operation-group']][] = $dict;
}

foreach ($grouped as $key => $value) {
    $api = [];

    // Extract the namespace and name from the 'x-operation-group'
    if (strpos($key, '.') !== false) {
        list($namespace, $name) = explode('.', $key);
    } else {
        $namespace = "__init__";
        $name = $key;
    }

    // Group the data in the current group by the "path" key
    $grouped_by_path = [];
    foreach ($value as $dict) {
        $grouped_by_path[$dict['path']][] = $dict;
    }

    $paths = [];
    $all_paths_have_deprecation = true;

    foreach ($grouped_by_path as $path => $path_dicts) {

        $methods = [];
        $parts_final = [];
        $deprecated_path_dict = [];

        foreach ($path_dicts as $method_dict) {
            $methods[] = strtoupper($method_dict['method']);

            if (!isset($api['documentation'])) {
                $api['documentation'] = ['description' => $method_dict['description']];
            }

            if (isset($method_dict["x-version-deprecated"])) {
                $deprecated_path_dict = array_merge($deprecated_path_dict, ["version" => $method_dict["x-version-deprecated"]]);
            }

            if (isset($method_dict["x-deprecation-message"])) {
                $deprecated_path_dict = array_merge($deprecated_path_dict, ["description" => $method_dict["x-deprecation-message"]]);
            } else {
                $all_paths_have_deprecation = false;
            }

            if (!isset($api['params']) && isset($method_dict['params'])) {
                $api['params'] = $method_dict['params'];
            }

            if (!isset($api['body']) && isset($method_dict['requestBody']) && isset($method_dict['requestBody']['$ref'])) {
                $requestbody_ref = explode('/', $method_dict['requestBody']['$ref']);
                $requestbody_ref = end($requestbody_ref);
                $body = ['required' => false];

                if (isset($data['components']['requestBodies'][$requestbody_ref]['required'])) {
                    $body['required'] = $data['components']['requestBodies'][$requestbody_ref]['required'];
                }

                if (isset($data['components']['requestBodies'][$requestbody_ref]['content']['application/x-ndjson'])) {
                    $requestbody_schema = $data['components']['requestBodies'][$requestbody_ref]['content']['application/x-ndjson']['schema'];
                    $body['serialize'] = "bulk";
                } else {
                    $requestbody_schema = $data['components']['requestBodies'][$requestbody_ref]['content']['application/json']['schema'];
                }

                if (isset($requestbody_schema['description'])) {
                    $body['description'] = $requestbody_schema['description'];
                }

                $api['body'] = $body;
            }

            if (isset($method_dict['parts'])) {
                $parts_final = array_merge($parts_final, $method_dict['parts']);
            }
        }

        // Update api dictionary with stability, visibility and headers
        if (in_array('POST', $methods) || in_array('PUT', $methods)) {
            $api['stability'] = 'stable';
            $api['visibility'] = 'public';
            $api['headers'] = [
                'accept' => ['application/json'],
                'content_type' => ['application/json'],
            ];
        } else {
            $api['stability'] = 'stable';
            $api['visibility'] = 'public';
            $api['headers'] = ['accept' => ['application/json']];
        }

        $path_data = ['path' => $path, 'methods' => $methods];

        if (!empty($deprecated_path_dict)) {
            $path_data['deprecated'] = $deprecated_path_dict;
        }

        if (!empty($parts_final)) {
            $path_data['parts'] = $parts_final;
        }

        $paths[] = $path_data;
    }

    $api['url'] = ['paths' => $paths];
    $files[] = [$key => $api];
}
// Generate endpoints
foreach ($files as $entry) {
    foreach ($entry as $key => $api) {
        if (empty($key) || ($key === '_common')) {
            continue;
        }

        printf("Generating %s...", $key);

        $entry_json = json_encode($entry);

        $endpoint = new Endpoint($key . '.json', $entry_json);

        $dir = $endpointDir . NamespaceEndpoint::normalizeName($endpoint->namespace);
        if (!file_exists($dir)) {
            mkdir($dir);
        }

        $outputFile = sprintf("%s/%s.php", $dir, $endpoint->getClassName());

        file_put_contents($outputFile, $endpoint->renderClass());

        if (!isValidPhpSyntax($outputFile)) {
            printf("Error: syntax error in %s\n", $outputFile);
            exit(1);
        }

        printf("done\n");

        $namespaces[$endpoint->namespace][] = $endpoint;
        $countEndpoint++;
    }
}


// Generate namespaces
$namespaceDir = "$outputDir/Namespaces/";
if (!file_exists($namespaceDir)) {
    mkdir($namespaceDir);
}

$countNamespace = 0;
$clientFile = "$outputDir/Client.php";

foreach ($namespaces as $name => $endpoints) {
    if (empty($name)) {
        $clientEndpoint = new ClientEndpoint(array_keys($namespaces));
        foreach ($endpoints as $ep) {
            $clientEndpoint->addEndpoint($ep);
        }
        file_put_contents(
            $clientFile,
            $clientEndpoint->renderClass()
        );
        if (!isValidPhpSyntax($clientFile)) {
            printf("Error: syntax error in %s\n", $clientFile);
            exit(1);
        }
        $countNamespace++;
        continue;
    }
    $namespace = new NamespaceEndpoint($name);
    foreach ($endpoints as $ep) {
        $namespace->addEndpoint($ep);
    }
    $namespaceFile = $namespaceDir . $namespace->getNamespaceName() . 'Namespace.php';

    file_put_contents(
        $namespaceFile,
        $namespace->renderClass()
    );

    if (!isValidPhpSyntax($namespaceFile)) {
        printf("Error: syntax error in %s\n", $namespaceFile);
        exit(1);
    }
    $countNamespace++;
}


$destDir = __DIR__ . "/../src/OpenSearch";

printf("Copying the generated files to %s\n", $destDir);
cleanFolders();
fix_license_header($outputDir . "/Namespaces");
fix_license_header($outputDir . "/Endpoints");
moveSubFolder($outputDir . "/Endpoints", $destDir . "/Endpoints");
moveSubFolder($outputDir . "/Namespaces", $destDir . "/Namespaces");
rename($outputDir . "/Client.php", $destDir . "/Client.php");

$end = microtime(true);
printf("\nGenerated %d endpoints and %d namespaces in %.3f seconds\n", $countEndpoint, $countNamespace, $end - $start);
printf("\n");

removeDirectory($outputDir);

/**
 * ---------------------------------- FUNCTIONS ----------------------------------
 */

/**
 * Remove a directory recursively
 */
function removeDirectory($directory, array $omit = [])
{
    foreach (glob("{$directory}/*") as $file) {
        if (is_dir($file)) {
            if (!in_array($file, $omit)) {
                removeDirectory($file, $omit);
            }
        } else {
            if (!in_array($file, $omit)) {
                @unlink($file);
            }
        }
    }
    if (is_dir($directory)) {
        @rmdir($directory);
    }
}

/**
 * Remove Endpoints, Namespaces and Client in src/OpenSearch
 */
function cleanFolders()
{
    removeDirectory(__DIR__ . '/../src/OpenSearch/Endpoints', [
        __DIR__ . '/../src/OpenSearch/Endpoints/AbstractEndpoint.php',
    ]);
    removeDirectory(__DIR__ . '/../src/OpenSearch/Namespaces', [
        __DIR__ . '/../src/OpenSearch/Namespaces/AbstractNamespace.php',
        __DIR__ . '/../src/OpenSearch/Namespaces/BooleanRequestWrapper.php',
        __DIR__ . '/../src/OpenSearch/Namespaces/NamespaceBuilderInterface.php'
    ]);
    @unlink(__DIR__ . '/../src/OpenSearch/Client.php');
}

/**
 * Move subfolder
 */
function moveSubFolder(string $origin, string $destination)
{
    foreach (glob("{$origin}/*") as $file) {
        rename($file, $destination . "/" . basename($file));
    }
}

/**
 * Backup Endpoints, Namespaces and Client in src/OpenSearch
 */
function backup(string $fileName)
{
    $zip = new ZipArchive();
    $result = $zip->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    if ($result !== true) {
        printf("Error opening the zip file %s: %s\n", $fileName, $result);
        exit(1);
    } else {
        $zip->addFile(__DIR__ . '/../src/OpenSearch/Client.php', 'Client.php');
        $zip->addGlob(__DIR__ . '/../src/OpenSearch/Namespaces/*.php', GLOB_BRACE, [
            'remove_path' => __DIR__ . '/../src/OpenSearch'
        ]);
        // Add the Endpoints (including subfolders)
        foreach (glob(__DIR__ . '/../src/OpenSearch/Endpoints/*') as $file) {
            if (is_dir($file)) {
                $zip->addGlob("$file/*.php", GLOB_BRACE, [
                    'remove_path' => __DIR__ . '/../src/OpenSearch'
                ]);
            } else {
                $zip->addGlob("$file", GLOB_BRACE, [
                    'remove_path' => __DIR__ . '/../src/OpenSearch'
                ]);
            }
        }
        $zip->close();
    }
}

/**
 * Restore Endpoints, Namespaces and Client in src/OpenSearch
 */
function restore(string $fileName)
{
    $zip = new ZipArchive();
    $result = $zip->open($fileName);
    if ($result !== true) {
        printf("Error opening the zip file %s: %s\n", $fileName, $result);
        exit(1);
    }
    $zip->extractTo(__DIR__ . '/../src/OpenSearch');
    $zip->close();
}

/**
 * Check if the generated code has a valid PHP syntax
 */
function isValidPhpSyntax(string $filename): bool
{
    if (file_exists($filename)) {
        $result = exec("php -l $filename");
        return false !== strpos($result, "No syntax errors");
    }
    return false;
};
