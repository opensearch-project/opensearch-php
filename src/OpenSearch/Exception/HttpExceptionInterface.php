<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Interface for HTTP error exceptions.
 */
interface HttpExceptionInterface extends OpenSearchExceptionInterface
{
    /**
     * Returns the status code.
     */
    public function getStatusCode(): int;

    /**
     * Returns response headers.
     */
    public function getHeaders(): array;
}
