<?php

declare(strict_types=1);

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

use Doctum\Doctum;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*Namespace.php')
    ->name('Client.php')
    ->name('ClientBuilder.php')
    ->notName('AbstractNamespace.php')
    ->in(__DIR__.'/../src/');

return new Doctum($iterator, [
    'theme'                => 'asciidoc',
    'template_dirs'        => [__DIR__.'/docstheme/'],
    'title'                => 'Elasticsearch-php',
    'build_dir'            => __DIR__.'/../docs/build',
    'cache_dir'            => __DIR__.'/cache/',
]);
