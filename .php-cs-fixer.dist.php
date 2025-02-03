<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

/*
This document has been generated with
https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0.0-rc.1|configurator
you can change this configuration by importing this file.
 */

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'no_unused_imports' => true,
        'fully_qualified_strict_types' => [
            'import_symbols' => true,
            'leading_backslash_in_global_namespace' => true,
            'phpdoc_tags' => ['property-read', 'property-write'],
        ],
        'no_extra_blank_lines' => ['tokens' => ['extra']],
    ])
    ->setFinder(
        Finder::create()
        ->exclude('vendor')
        ->exclude('util/cache')
        ->in(__DIR__)
    )
;
