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

namespace OpenSearch\ConnectionPool;

use OpenSearch\Connections\ConnectionInterface;

interface ConnectionPoolInterface
{
    public function nextConnection(bool $force = false): ConnectionInterface;

    public function scheduleCheck(): void;
}
