<?php

namespace ChrisUllyott\FileSize;

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
        'B'  => ['b',              'byte',      'bytes'],
        'KB' => ['k', 'kb', 'kib', 'kilobyte',  'kilobytes'],
        'MB' => ['m', 'mb', 'mib', 'megabyte',  'megabytes'],
        'GB' => ['g', 'gb', 'gib', 'gigabyte',  'gigabytes'],
        'TB' => ['t', 'tb', 'tib', 'terabyte',  'terabytes'],
        'PB' => ['p', 'pb', 'pib', 'petabyte',  'petabytes'],
        'EB' => ['e', 'eb', 'eib', 'exabyte',   'exabytes'],
        'ZB' => ['z', 'zb', 'zib', 'zettabyte', 'zettabytes'],
        'YB' => ['y', 'yb', 'yib', 'yottabyte', 'yottabytes']
    ];
}
