<?php

namespace ChrisUllyott\FileSize;

class SizeStringParser
{
    /**
     * A regex pattern for matching size strings, such as:
     *
     * - 10k
     * - 123 MB
     * - 1 gigabytes
     */
    const SIZE_STRING_PATTERN = '/^([0-9\.]+)\s*?([A-Za-z]+)$/';

    /**
     * Parse a size string into its parts (value, unit).
     *
     * @param string $sizeString Such as '100 MB'
     * @return object
     */
    public static function parse($sizeString)
    {
        $sizeString = trim($sizeString);

        if (is_numeric($sizeString)) {
            return self::parseNumericString($sizeString);
        }

        return self::parseNonNumericString($sizeString);
    }

    /**
     * Parse a numeric size string (bytes) into its parts (value, 'B'). Numeric
     * strings are expected to be a byte count, so decimal-point values would throw
     * an Exception.
     *
     * @param  string $string Numeric such as '1000'
     * @return object
     */
    private static function parseNumericString($string)
    {
        $intVal = intval($string);
        $floatVal = floatval($string);

        if ($floatVal == $intVal) {
            return (object) ['value' => $intVal, 'unit' => 'B'];
        }

        throw new Exception("Missing unit for float \"{$floatVal}\"");
    }

    /**
     * Parse a size string into its parts (value, unit).
     *
     * @param string $string Such as '100 MB'
     * @return object
     */
    private static function parseNonNumericString($string)
    {
        preg_match(self::SIZE_STRING_PATTERN, $string, $matches);

        if (count($matches) === 3) {
            $floatVal = floatval($matches[1]);
            $unit = $matches[2];
            return (object) ['value' => $floatVal, 'unit' => $unit];
        }

        throw new Exception("Could not parse \"{$string}\"");
    }
}
