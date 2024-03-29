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
     * - 123,4 MB
     * - 1 gigabytes
     */
    const SIZE_STRING_PATTERN = '/^(-?[0-9\.\, ]+)\s*([a-zA-Z]+)?$/';

    /**
     * Parse a size string into its parts (value, unit).
     *
     * @param string|int $size Such as '100 MB'
     * @return object
     */
    public static function parse($size)
    {
        preg_match(self::SIZE_STRING_PATTERN, $size, $matches);

        if (empty($matches[1])) {
            throw new FileSizeException("Could not parse \"{$size}\"");
        }

        return (object) ['value' => $matches[1], 'unit' => $matches[2] ?? null];
    }
}
