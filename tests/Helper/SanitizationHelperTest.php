<?php declare(strict_types=1);


namespace OpenSearch\Tests\Helper;


use OpenSearch\Helper\SanitizationHelper;

class SanitizationHelperTest extends \PHPUnit\Framework\TestCase
{
    public function testEscapeReservedChars()
    {
        $testList = [
            'abc' => 'abc',
            '+' => '\+',
            '-' => '\-',
            '=' => '\=',
            '&' => '\&',
            '|' => '\|',
            '!' => '\!',
            '(' => '\(',
            ')' => '\)',
            '{' => '\{',
            '}' => '\}',
            '[' => '\[',
            ']' => '\]',
            '^' => '\^',
            '"' => '\"',
            '~' => '\~',
            '*' => '\*',
            '<' => '\<',
            '>' => '\>',
            '?' => '\?',
            ':' => '\:',
            '\\' => '\\\\',
            '/' => '\/',
        ];

        foreach ($testList as $string => $expected) {
            $this->assertSame(
                $expected,
                SanitizationHelper::escapeReservedChars($string),
                "$string is not the same as $expected"
            );
        }
    }
}
