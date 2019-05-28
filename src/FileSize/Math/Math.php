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

    /**
     * Format a numeric string into a byte count (integer).
     *
     * @param  string $number A numeric string or float
     * @return int
     */
    public static function byteFormat($number)
    {
        return (int) ceil($number);
    }
}
