<?php

declare(strict_types=1);

namespace OpenSearch;

/**
 * Represents a request.
 */
class Request
{
    public function __construct(
        private readonly string $method,
        private readonly string $uri,
        private readonly array $params = [],
        private readonly string|array|null $body = null,
        private readonly array $headers = [],
    ) {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getBody(): string|array|null
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

}
