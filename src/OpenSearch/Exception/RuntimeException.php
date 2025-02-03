<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

use OpenSearch\Common\Exceptions\OpenSearchException;

/**
 * An exception thrown when a runtime error occurs.
 *
 * @phpstan-ignore class.implementsDeprecatedInterface
 */
class RuntimeException extends \RuntimeException implements OpenSearchException
{
}
