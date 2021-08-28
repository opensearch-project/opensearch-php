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

namespace OpenSearch\ConnectionPool\Selectors;

use OpenSearch\Connections\ConnectionInterface;

interface SelectorInterface
{
    /**
     * Perform logic to select a single ConnectionInterface instance from the array provided
     *
     * @param \OpenSearch\Connections\ConnectionInterface[] $connections an array of ConnectionInterface instances to choose from
     */
    public function select(array $connections): ConnectionInterface;
}
