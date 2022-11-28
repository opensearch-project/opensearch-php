<?php declare(strict_types=1);


namespace OpenSearch\Helper;


class SanitizationHelper
{
    /**
     * @param string $string
     *
     * @return string
     */
    public static function escapeReservedChars(string $string): string
    {
        return preg_replace(
            "/[\\+\\-\\|\\(\\)\\\"\\~\\*\\<\\>\\\\]/",
            addslashes('\\$0'),
            $string
        );
    }
}
