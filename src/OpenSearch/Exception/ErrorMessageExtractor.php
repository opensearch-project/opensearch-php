<?php

declare(strict_types=1);

namespace OpenSearch\Exception;

/**
 * Extracts an error message from the response body.
 */
class ErrorMessageExtractor
{
    public static function extractErrorMessage(string|array|null $data): ?string
    {
        if (is_string($data) || is_null($data)) {
            return $data;
        }

        // Must be an array now.
        if (!isset($data['error'])) {
            // <2.0 "i just blew up" non-structured exception.
            // $error is an array, but we don't know the format, so we reuse the response body instead
            // added json_encode to convert into a string
            return json_encode($data);
        }

        // 2.0 structured exceptions
        if (is_array($data['error']) && array_key_exists('reason', $data['error'])) {
            // Try to use root cause first (only grabs the first root cause)
            $info = $data['error']['root_cause'][0] ?? $data['error'];
            $cause = $info['reason'];
            $type = $info['type'];

            return "$type: $cause";
        }

        // <2.0 semi-structured exceptions
        $legacyError = $data['error'];
        if (is_array($legacyError)) {
            return json_encode($legacyError);
        }
        return $legacyError;
    }
}
