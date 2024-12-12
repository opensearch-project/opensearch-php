<?php

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

$LICENSE_HEADER = "/**\n" .
" * SPDX-License-Identifier: Apache-2.0\n" .
" *\n" .
" * The OpenSearch Contributors require contributions made to\n" .
" * this file be licensed under the Apache-2.0 license or a\n" .
" * compatible open source license.\n" .
" *\n" .
" * Modifications Copyright OpenSearch Contributors. See\n" .
" * GitHub history for details.\n" .
" */\n";


function doesFileNeedFix(string $filepath): bool
{
    $content = file_get_contents($filepath);
    return strpos($content, 'Copyright OpenSearch') === false;
}

function addHeaderToFile(string $filepath): void
{
    $lines = file($filepath);
    global $LICENSE_HEADER;
    foreach ($lines as $i => $line) {
        if (strpos($line, 'declare(strict_types=1);') !== false) {
            array_splice($lines, $i + 1, 0, "\n" . $LICENSE_HEADER . "\n");
            break;
        }
    }
    file_put_contents($filepath, implode('', $lines));
    echo "Fixed " . realpath($filepath) . "\n";
}

function fix_license_header(string $path): void
{
    if (is_file($path)) {
        if (doesFileNeedFix($path)) {
            addHeaderToFile($path);
        }
    } elseif (is_dir($path)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && doesFileNeedFix($file->getPathname())) {
                addHeaderToFile($file->getPathname());
            }
        }
    }
}
