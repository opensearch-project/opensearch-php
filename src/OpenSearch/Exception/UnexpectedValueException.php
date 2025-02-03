<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

use OpenSearch\Common\Exceptions\OpenSearchException;

/**
 * An exception thrown when invalid arguments are passed to a method.
 *
 * @phpstan-ignore class.implementsDeprecatedInterface
 */
class UnexpectedValueException extends \UnexpectedValueException implements OpenSearchException
{
}
