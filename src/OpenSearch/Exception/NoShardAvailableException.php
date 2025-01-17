<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Exception thrown when a 500 Internal Server Error HTTP error occurs for No Shard Available.
 */
class NoShardAvailableException extends InternalServerErrorHttpException
{
}
