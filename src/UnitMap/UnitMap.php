<?php

/**
 * A data structure for the unit map.
 */

namespace ChrisUllyott\FileSize\UnitMap;

class UnitMap
{
    /**
     * The key used for bytes, the base unit.
     */
    const BYTE = 'B';

    /**
     * A mapping of filesize units to lowercase strings.
     *
     * @var array
     */
    public static $map = [
        'B'  => ['b', 'byte'],
        'KB' => ['k', 'kb', 'kib', 'kilobyte',  'kibibyte'],
        'MB' => ['m', 'mb', 'mib', 'megabyte',  'mebibyte'],
        'GB' => ['g', 'gb', 'gib', 'gigabyte',  'gibibyte'],
        'TB' => ['t', 'tb', 'tib', 'terabyte',  'tebibyte'],
        'PB' => ['p', 'pb', 'pib', 'petabyte',  'pebibyte'],
        'EB' => ['e', 'eb', 'eib', 'exabyte',   'exbibyte'],
        'ZB' => ['z', 'zb', 'zib', 'zettabyte', 'zebibyte'],
        'YB' => ['y', 'yb', 'yib', 'yottabyte', 'yobibyte']
    ];
}
