<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

use OpenSearch\Common\Exceptions\OpenSearchException;

/**
 * Provides an exception for JSON errors.
 *
 * @phpstan-ignore class.implementsDeprecatedInterface
 */
class JsonException extends \JsonException implements OpenSearchException
{
    public function __construct(int $code, private readonly ?string $data, ?\Throwable $previous = null)
    {
        parent::__construct($this->getErrorMessage($code), $code, $previous);
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * Gets the JSON error message for the given error code.
     */
    private function getErrorMessage(int $code): string
    {
        return match ($code) {
            JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
            JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded',
            JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded',
            JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given',

            // JSON_ERROR_* constant values that are available on PHP >= 7.0
            9 => 'Decoding of value would result in invalid PHP property name', //JSON_ERROR_INVALID_PROPERTY_NAME
            10 => 'Attempted to decode nonexistent UTF-16 code-point',//JSON_ERROR_UTF16
            default => throw new \InvalidArgumentException("Encountered unknown JSON error code: [{$code}]"),
        };
    }
}
