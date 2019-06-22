<?php

/**
 * Basic math for filesizes.
 */

namespace ChrisUllyott\FileSize\Math;

use ChrisUllyott\FileSize\Exception\FileSizeException;

class Math
{
    /**
     * Get the number of bytes per factor.
     *
     * @param  int $factor
     * @return int
     */
    public static function bytesByFactor($factor, $base)
    {
        if ($base === 2) {
            return 1024 ** $factor;
        } elseif ($base === 10) {
            return 1000 ** $factor;
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
