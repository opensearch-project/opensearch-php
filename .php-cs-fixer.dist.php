<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0.0-rc.1|configurator
 * you can change this configuration by importing this file.
 */

$header = <<<HEADER
SPDX-License-Identifier: Apache-2.0

The OpenSearch Contributors require contributions made to
this file be licensed under the Apache-2.0 license or a
compatible open source license.

Modifications Copyright OpenSearch Contributors. See
GitHub history for details.
HEADER;


return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $header,
        ]
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->in(__DIR__)
    )
    ;