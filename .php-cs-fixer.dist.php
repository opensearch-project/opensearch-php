<?php
/*
This document has been generated with
https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0.0-rc.1|configurator
you can change this configuration by importing this file.
 */

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->exclude('util/cache')
        ->in(__DIR__)
    )
    ;