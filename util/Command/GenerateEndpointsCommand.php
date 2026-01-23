<?php

declare(strict_types=1);

namespace OpenSearch\Util\Command;

use OpenSearch\Util\ClientEndpoint;
use OpenSearch\Util\Endpoint;
use OpenSearch\Util\NamespaceEndpoint;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(name: 'app:generate-endpoints', description: 'Generates endpoints for OpenSearch')]
class GenerateEndpointsCommand extends Command
{
    public const LICENSE_HEADER = <<<'LICENSE'
/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */
LICENSE;

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $start = microtime(true);
        $io->info('Generating endpoints for OpenSearch');

        $success = true;

        // Load the OpenAPI specification file
        $url = "https://github.com/opensearch-project/opensearch-api-specification/releases/download/main-latest/opensearch-openapi.yaml";
        $yamlContent = file_get_contents($url);
        $data = Yaml::parse($yamlContent);

        $list_of_dicts = [];
        foreach ($data["paths"] as $path => $pathDetails) {
            foreach ($pathDetails as $method => $methodDetails) {
                if (isset($methodDetails["x-operation-group"]) && $methodDetails["x-operation-group"] == "nodes.hot_threads") {
                    if (isset($methodDetails["deprecated"]) && $methodDetails["deprecated"]) {
                        continue;
                    }
                }
                $methodDetails["path"] = $path;
                $methodDetails["method"] = $method;
                $list_of_dicts[] = $methodDetails;
            }
        }

        $outputDir = dirname(__DIR__) . "/output";
        if (!file_exists($outputDir)) {
            mkdir($outputDir);
        }

        $endpointDir = "$outputDir/Endpoints/";
        if (!file_exists($endpointDir)) {
            mkdir($endpointDir);
        }

        $countEndpoint = 0;
        $namespaces = [];

        foreach ($list_of_dicts as $index => $endpoint) {
            if (array_key_exists("parameters", $endpoint)) {
                $params = [];
                $parts = [];

                // Iterate over the list of parameters and update them
                foreach ($endpoint["parameters"] as $param_ref) {
                    $param_ref_value = substr(
                        $param_ref["$" . "ref"],
                        strrpos($param_ref["$" . "ref"], '/') + 1
                    );
                    $param = $data["components"]["parameters"][$param_ref_value];
                    if (isset($param["schema"]) && isset($param["schema"]["$" . "ref"])) {
                        $schema_path_ref = substr(
                            $param["schema"]["$" . "ref"],
                            strrpos($param["schema"]["$" . "ref"], '/') + 1
                        );
                        $param["schema"] = $data["components"]["schemas"][$schema_path_ref];
                        $params[] = $param;
                    } else {
                        $params[] = $param;
                    }
                }

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
                        $param_dict['description'] = preg_replace(
                            '/\s+/',
                            ' ',
                            $param['description']
                        );
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

                if ($endpoint['x-operation-group'] === 'cat.tasks') {
                    $params_new['node_id'] = $params_new['nodes'] ?? $params_new['node_id'];
                    unset($params_new['nodes']);

                    $params_new['parent_task'] = $params_new['parent_task_id'] ?? $params_new['parent_task'];
                    unset($params_new['parent_task_id']);
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
                                if ($item['type'] == "array") {
                                    break;
                                }
                            }
                        }
                    }
                    if ($endpoint['x-operation-group'] === 'cluster.get_component_template' || $endpoint['x-operation-group'] === 'indices.get_index_template') {
                        $part['name'] = "name";
                        $parts_dict['type'] = 'array';
                    }

                    if (isset($part['description'])) {
                        $parts_dict['description'] = str_replace(
                            "\n",
                            " ",
                            $part['description']
                        );
                    }

                    if (isset($part['schema']['x-enum-options'])) {
                        $parts_dict['options'] = $part['schema']['x-enum-options'];
                    }

                    if (isset($part['deprecated'])) {
                        $parts_dict['deprecated'] = $part['deprecated'];
                    }

                    # To prevent breaking change, replaced below path parameter to 'id'

                    $operationGroups = [
                        'ml.delete_model' => 'model_id',
                        'ml.delete_model_group' => 'model_group_id',
                        'ml.get_task' => 'task_id',
                    ];

                    foreach ($operationGroups as $group => $name) {
                        if ($endpoint['x-operation-group'] === $group && $part['name'] === $name) {
                            $part['name'] = 'id';
                            break;
                        }
                    }

                    $parts_new[$part['name']] = $parts_dict;
                }

                if (!empty($parts_new)) {
                    $endpoint['parts'] = $parts_new;
                }

                if ($endpoint['x-operation-group'] === 'nodes.info' && $endpoint['path'] == '/_nodes/{node_id_or_metric}') {
                    # add two more endpoints, one for just node id and another for just metric for backwards compatibility
                    # https://github.com/opensearch-project/opensearch-api-specification/pull/416
                    foreach (
                        [
                            'metric' => 'Limits the information returned to the specific metrics. Supports a comma-separated list, such as http,ingest.',
                            'node_id' => 'Comma-separated list of node IDs or names used to limit returned information.',
                        ] as $param => $desc
                    ) {
                        $endpoint_new = $endpoint;
                        $endpoint_new['path'] = '/_nodes/{' . $param . '}';
                        $endpoint_new['parameters'][0] = ['$' . 'ref' => '#/components/parameters/nodes.info::path.' . $param];
                        $endpoint_new['parts'] = [
                            $param => [
                                'type' => 'array',
                                'description' => $desc,
                            ],
                        ];
                        $list_of_dicts[] = $endpoint_new;
                    }
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
            if (str_contains($key, '.')) {
                [$namespace, $name] = explode('.', $key);
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
                        $deprecated_path_dict = array_merge(
                            $deprecated_path_dict,
                            ["version" => $method_dict["x-version-deprecated"]]
                        );
                    }

                    if (isset($method_dict["x-deprecation-message"])) {
                        $deprecated_path_dict = array_merge(
                            $deprecated_path_dict,
                            ["description" => $method_dict["x-deprecation-message"]]
                        );
                    } else {
                        $all_paths_have_deprecation = false;
                    }

                    if (!isset($api['params']) && isset($method_dict['params'])) {
                        $api['params'] = $method_dict['params'];
                    }

                    if (!isset($api['body']) && isset($method_dict['requestBody']) && isset($method_dict['requestBody']['$ref'])) {
                        $requestbody_ref = explode(
                            '/',
                            $method_dict['requestBody']['$ref']
                        );
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
                        $parts_final = array_merge(
                            $parts_final,
                            $method_dict['parts']
                        );
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

                foreach ($operationGroups as $group => $name) {
                    if ($key === $group) {
                        $path = str_replace($name, 'id', $path);
                    }
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
            if ($all_paths_have_deprecation && $deprecated_path_dict !== null) {
                $api['deprecation_message'] = $deprecated_path_dict['description'];
            }
            $files[] = [$key => $api];
        }
        // Generate endpoints
        foreach ($files as $entry) {
            foreach ($entry as $key => $api) {
                $io->write(sprintf("Generating %s...", $key));
                $entry_json = json_encode($entry);
                $endpoint = new Endpoint($key . '.json', $entry_json);

                $dir = $endpointDir . NamespaceEndpoint::normalizeName(
                    $endpoint->namespace
                );
                if (!file_exists($dir)) {
                    mkdir($dir);
                }
                $outputFile = sprintf(
                    "%s/%s.php",
                    $dir,
                    $endpoint->getClassName()
                );
                file_put_contents($outputFile, $endpoint->renderClass());
                if (!$this->isValidPhpSyntax($outputFile)) {
                    $io->error(sprintf("syntax error in %s\n", $outputFile));
                    return Command::FAILURE;
                }

                $io->writeln("done ✅️");

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
                if (!$this->isValidPhpSyntax($clientFile)) {
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
            $namespaceFile = $namespaceDir . $namespace->getNamespaceName(
            ) . 'Namespace.php';
            file_put_contents(
                $namespaceFile,
                $namespace->renderClass()
            );
            if (!$this->isValidPhpSyntax($namespaceFile)) {
                printf("Error: syntax error in %s\n", $namespaceFile);
                exit(1);
            }
            $countNamespace++;
        }

        $destDir = \dirname(__DIR__, 2) . '/src/OpenSearch';

        $io->info(sprintf("Copying the generated files to %s", $destDir));
        $this->patchEndpoints();
        $this->cleanFolders();
        $this->fixLicenseHeader($outputDir . "/Namespaces");
        $this->fixLicenseHeader($outputDir . "/Endpoints");
        $this->moveSubFolder($outputDir . "/Endpoints", $destDir . "/Endpoints");
        $this->moveSubFolder($outputDir . "/Namespaces", $destDir . "/Namespaces");
        rename($outputDir . "/Client.php", $destDir . "/Client.php");

        $this->removeDirectory($outputDir);

        $end = microtime(true);
        $io->info(sprintf(
            "Generated %d endpoints and %d namespaces in %.3f seconds\n",
            $countEndpoint,
            $countNamespace,
            $end - $start
        ));

        return Command::SUCCESS;
    }

    /**
     * Remove a directory recursively
     */
    public function removeDirectory($directory, array $omit = []): void
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                if (!in_array($file, $omit)) {
                    $this->removeDirectory($file, $omit);
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
    public function cleanFolders(): void
    {
        $rootDir = \dirname(__DIR__, 2);
        $this->removeDirectory($rootDir . '/src/OpenSearch/Endpoints', [
            $rootDir . '/src/OpenSearch/Endpoints/AbstractEndpoint.php',
        ]);
        $this->removeDirectory($rootDir . '/src/OpenSearch/Namespaces', [
            $rootDir . '/src/OpenSearch/Namespaces/AbstractNamespace.php',
            $rootDir . '/src/OpenSearch/Namespaces/BooleanRequestWrapper.php',
            $rootDir . '/src/OpenSearch/Namespaces/NamespaceBuilderInterface.php',
        ]);
        @unlink($rootDir . '/src/OpenSearch/Client.php');
    }

    /**
     * Move subfolder
     */
    public function moveSubFolder(string $origin, string $destination): void
    {
        foreach (glob("{$origin}/*") as $file) {
            rename($file, $destination . "/" . basename($file));
        }
    }

    /**
     * Backup Endpoints, Namespaces and Client in src/OpenSearch
     */
    public function backup(string $fileName): void
    {
        $zip = new \ZipArchive();
        $result = $zip->open(
            $fileName,
            \ZipArchive::CREATE | \ZipArchive::OVERWRITE
        );
        if ($result !== true) {
            printf("Error opening the zip file %s: %s\n", $fileName, $result);
            exit(1);
        } else {
            $zip->addFile(
                \dirname(__DIR__, 2) . '/src/OpenSearch/Client.php',
                'Client.php'
            );
            $zip->addGlob(
                \dirname(__DIR__, 2) . '/src/OpenSearch/Namespaces/*.php',
                GLOB_BRACE,
                [
                    'remove_path' => \dirname(__DIR__, 2) . '/src/OpenSearch',
                ]
            );
            // Add the Endpoints (including subfolders)
            foreach (
                glob(
                    \dirname(__DIR__, 2) . '/src/OpenSearch/Endpoints/*'
                ) as $file
            ) {
                if (is_dir($file)) {
                    $zip->addGlob("$file/*.php", GLOB_BRACE, [
                        'remove_path' => \dirname(__DIR__, 2) . '/src/OpenSearch',
                    ]);
                } else {
                    $zip->addGlob("$file", GLOB_BRACE, [
                        'remove_path' => \dirname(__DIR__, 2) . '/src/OpenSearch',
                    ]);
                }
            }
            $zip->close();
        }
    }

    /**
     * Restore Endpoints, Namespaces and Client in src/OpenSearch
     */
    public function restore(string $fileName): void
    {
        $zip = new \ZipArchive();
        $result = $zip->open($fileName);
        if ($result !== true) {
            printf("Error opening the zip file %s: %s\n", $fileName, $result);
            exit(1);
        }
        $zip->extractTo(\dirname(__DIR__, 2) . '/src/OpenSearch');
        $zip->close();
    }

    /**
     * Check if the generated code has a valid PHP syntax
     */
    public function isValidPhpSyntax(string $filename): bool
    {
        if (file_exists($filename)) {
            $result = exec("php -l $filename");
            return str_contains($result, "No syntax errors");
        }
        return false;
    }

    /**
     * Patching Endpoints that do not have OpenSearch API specifications
     */
    public function patchEndpoints(): void
    {
        $patchEndpoints = [
            'AsyncSearch',
            'SearchableSnapshots',
            'Ssl',
            'Sql',
            'DataFrameTransformDeprecated',
            'Monitoring',
            'Indices/RefreshSearchAnalyzers',
            'Ml/CreateConnector',
            'Ml/DeleteConnector',
            'Ml/GetConnector',
            'Ml/GetConnectors',
            'Ml/GetModelGroups',
            'Ml/UpdateModelGroup',
            'Ml/DeployModel',
            'Ml/GetModel',
            'Ml/Predict',
            'Ml/UndeployModel',
        ];
        $outputDir = dirname(__DIR__) . "/output";
        $destDir = dirname(__DIR__, 2) . "/src/OpenSearch";

        $foldersToCheck = ['Endpoints', 'Namespaces'];

        foreach ($foldersToCheck as $folder) {
            $dirPath = "$destDir/$folder";
            if (!is_dir($dirPath)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $dirPath,
                    \FilesystemIterator::SKIP_DOTS
                )
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $filePath = $file->getPathname();
                    foreach ($patchEndpoints as $endpoint) {
                        if (str_contains($filePath, $endpoint)) {
                            $relativePath = str_replace(
                                $destDir,
                                '',
                                $filePath
                            );
                            $targetPath = $outputDir . $relativePath;

                            if (!file_exists($targetPath)) {
                                is_dir(dirname($targetPath)) || mkdir(
                                    dirname($targetPath),
                                    0777,
                                    true
                                );
                                copy($filePath, $targetPath);
                            }
                        }
                    }
                }
            }
        }
    }

    public function doesFileNeedFix(string $filepath): bool
    {
        $content = file_get_contents($filepath);
        return !str_contains($content, 'Copyright OpenSearch');
    }

    public function addHeaderToFile(string $filepath): void
    {
        $lines = file($filepath);
        foreach ($lines as $i => $line) {
            if (str_contains($line, 'declare(strict_types=1);')) {
                array_splice(
                    $lines,
                    $i + 1,
                    0,
                    "\n" . static::LICENSE_HEADER . "\n"
                );
                break;
            }
        }
        file_put_contents($filepath, implode('', $lines));
        echo "Fixed " . realpath($filepath) . "\n";
    }

    public function fixLicenseHeader(string $path): void
    {
        if (is_file($path)) {
            if ($this->doesFileNeedFix($path)) {
                $this->addHeaderToFile($path);
            }
        } elseif (is_dir($path)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $path,
                    \FilesystemIterator::SKIP_DOTS
                ),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($iterator as $file) {
                if ($file->isFile() && $this->doesFileNeedFix(
                    $file->getPathname()
                )) {
                    $this->addHeaderToFile($file->getPathname());
                }
            }
        }
    }

}
