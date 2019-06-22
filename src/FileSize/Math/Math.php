<?php

/**
 * Basic math for filesizes.
 */

namespace ChrisUllyott\FileSize\Math;

use ChrisUllyott\FileSize\Exception\FileSizeException;

class Math
{
    /**
     * Get the number of bytes per factor. In base 2, the first factor is 1024,
     * while in decimal it is 1000.
     *
     * @param  int $factor
     * @return int
     */
    public static function bytesByFactor($factor, $base)
    {
        if ($base === 2) {
            return $base ** (10 * $factor);
        } elseif ($base === 10) {
            return $base ** (3 * $factor);
        }

        throw new FileSizeException('Invalid number base (use either 2 or 10)');
    }

    /**
     * Get the factor for a given number of bytes.
     *
     * @param  int $bytes
     * @return int
     */
    public static function factorByBytes($bytes)
    {
        return floor((strlen($bytes) - 1) / 3);
    }
}
