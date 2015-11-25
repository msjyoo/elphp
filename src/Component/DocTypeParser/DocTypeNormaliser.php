<?php

namespace Elphp\Component\DocTypeParser;

/**
 * Class DocTypeNormaliser
 * @package Elphp\Component\DocTypeParser
 */
final class DocTypeNormaliser
{
    /**
     * Normalise an array or string of PHPDoc comment. e.g. integer -> int
     *
     * @param array|string $type
     *
     * @return array|string
     */
    public static function normalise($type)
    {
        if(is_array($type))
        {
            return array_unique(array_map(function ($part) {
                return self::normalisePart($part);
            }, $type));
        }

        return implode("|", array_unique(array_map(function ($part) {
            return self::normalisePart($part);
        }, explode("|", (string) $type))));
    }

    /**
     * Normalise a single PHPDoc keyword
     *
     * @param string $string
     *
     * @return string
     */
    public static function normalisePart($string)
    {
        switch($string)
        {
            case "integer":
                return "int";
            case "boolean":
                return "bool";
            case "double":
                return "float";
            case "number":
                return "float"; // Be safe in operations, prohibit integer-only ops TODO: actually this should return both int and float
            case "NULL":
                return "null";
            default:
                return $string;
        }
    }

    /**
     * Checks if a keyword is a part of PHPDoc Types
     *
     * @param string $string A keyword
     *
     * @return bool Returns true if the keyword is a part of PHPDoc Types
     */
    public static function isKeyword($string)
    {
        switch($string)
        {
            case "string":
            case "integer":
            case "int":
            case "boolean":
            case "bool":
            case "float":
            case "double":
            case "object":
            case "mixed":
            case "array":
            case "resource":
            case "void":
            case "null":
            case "callable":
            case "false":
            case "true":
            case "self":
            case "static":
            case '$this':
                return true;
            /* Not an official PHPDoc Type, but useful to treat it as a keyword */
            case "number":
                return true;
            default:
                return false;
        }
    }

    /**
     * Sanitise a tag string for use in parsing.
     * In some cases, a tag may be spread out over multiple lines, such as a malformed return tag.
     * This function sanitises those tags so newlines and HTML tags are removed.
     *
     * Example:
     *
     * Turns:
     * (at)return string
     * $string
     *
     * Into: (at)return string $string
     *
     * @param string $string
     *
     * @return string
     */
    public static function sanitise($string)
    {
        return str_replace("\n", "", str_replace("\r\n", "\n", strip_tags($string)));
    }
}