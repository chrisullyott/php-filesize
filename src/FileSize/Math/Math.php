<?php

/**
 * Basic math for filesizes.
 */

namespace ChrisUllyott\FileSize\Math;

class Math
{
    /**
     * Get the number of bytes per factor. (2^10 = 1,024; 2^20 = 1,048,576...)
     *
     * @param  int $factor
     * @return int
     */
    public static function bytesByFactor($factor)
    {
        return 2 ** (10 * $factor);
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
