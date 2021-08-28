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

namespace OpenSearch\Common;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Class EmptyLogger
 *
 * Logger that doesn't do anything.  Similar to Monolog's NullHandler,
 * but avoids the overhead of partially loading Monolog
 */
class EmptyLogger extends AbstractLogger implements LoggerInterface
{
    /**
     * {@inheritDoc}
     */
    public function log($level, $message, array $context = [])
    {
        return;
    }
}
