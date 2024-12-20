<?php

namespace OpenSearch;

/**
 * Provides and interface for endpoints.
 */
interface EndpointInterface
{
    /**
     * Get the whitelist of allowed parameters.
     *
     * @return string[]
     */
    public function getParamWhitelist(): array;

    /**
     * Get the URI.
     */
    public function getURI(): string;

    /**
     * Get the HTTP method.
     */
    public function getMethod(): string;

    /**
     * Set the query string parameters.
     */
    public function setParams(array $params): static;

    /**
     * Get the query string parameters.
     */
    public function getParams(): array;

    /**
     * Get the options.
     *
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * Get the index.
     */
    public function getIndex(): ?string;

    /**
     * Set the index.
     *
     * @param string|string[]|null $index
     *
     * @return $this
     */
    public function setIndex(string|array|null $index): static;

    /**
     * Get the document ID.
     *
     * @param int|string|null $docID
     *
     * @return $this
     */
    public function setId(int|string|null $docID): static;

    /**
     * Get the body of the request.
     */
    public function getBody(): string|array|null;

    /**
     * Set the body of the request.
     *
     * @param string|iterable<string,mixed>|null $body
     */
    public function setBody(string|iterable|null $body): static;

}
