<?php

declare(strict_types=1);

namespace OpenSearch;

/**
 * Represents an OpenSearch response.
 */
class Response
{
    public function __construct(
        private readonly int $statusCode = 200,
        private readonly array $headers = [],
        private readonly string|array|null $body = null,
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string|array|null
    {
        return $this->body;
    }
}
