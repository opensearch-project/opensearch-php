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

namespace OpenSearch\Common\Exceptions;

/**
 * BadMethodCallException
 *
 * Denote problems with a method call (e.g. incorrect number of arguments)
 */
class BadMethodCallException extends \BadMethodCallException implements OpenSearchException
{
}
