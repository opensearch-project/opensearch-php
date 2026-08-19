<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * OpenSearch PHP client
 *
 * @link      https://github.com/opensearch-project/opensearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Connections;

// @phpstan-ignore classConstant.deprecatedInterface
@trigger_error(ConnectionFactoryInterface::class . ' is deprecated in 2.4.0 and will be removed in 3.0.0.', E_USER_DEPRECATED);

/**
 * @deprecated in 2.4.0 and will be removed in 3.0.0.
 */
interface ConnectionFactoryInterface
{
    /**
     * @param array{host: string, port?: int, scheme?: string, user?: string, pass?: string, path?: string} $hostDetails
     */
    public function create(array $hostDetails): ConnectionInterface;
}
