<?php

/**
 * Parse a size string like '100 MB' into its parts (value, unit).
 */

namespace ChrisUllyott\FileSize\Parser;

use ChrisUllyott\FileSize\Exception\FileSizeException;

class SizeStringParser
{
    /**
     * A regex pattern for matching size strings, such as:
     *
     * - 150
     * - 10k
     * - 123 MB
     * - 1 gigabytes
     */
    const SIZE_STRING_PATTERN = '/^([0-9\.-]+)\s*?([A-Za-z]+)?$/';

    /**
     * Parse a size string into its parts (value, unit).
     *
     * @param string|int $size Such as '100 MB'
     * @return object
     */
    public static function parse($size)
    {
        preg_match(self::SIZE_STRING_PATTERN, $size, $matches);

        if (!isset($matches[1]) || !is_numeric($matches[1])) {
            throw new FileSizeException("Could not parse \"{$size}\"");
        }

        $value = $matches[1];
        $unit = isset($matches[2]) ? $matches[2] : null;

        return (object) ['value' => $value, 'unit' => $unit];
    }
}
