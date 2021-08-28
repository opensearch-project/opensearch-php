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

error_reporting(E_ALL | E_STRICT);

// Set the default timezone. While this doesn't cause any tests to fail, PHP
// complains if it is not set in 'date.timezone' of php.ini.
date_default_timezone_set('UTC');

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die(
        "Dependencies must be installed using composer:\n\nphp composer.phar install --dev\n\n"
        . "See http://getcomposer.org for help with installing composer\n"
    );
}

// Include the composer autoloader
$autoloader = include_once dirname(__DIR__) . '/vendor/autoload.php';
