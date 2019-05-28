<?php

namespace ChrisUllyott\FileSize;

class UnitMap
{
    /**
     * A mapping of filesize units to lowercase strings.
     *
     * @var array
     */
    private static $map = [
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

    public static function map()
    {
        return self::$map;
    }

    public static function keys()
    {
        return array_keys(self::$map);
    }

    public static function lookup($unitString)
    {
        $lowerUnitString = strtolower($unitString);

        foreach (self::$map as $key => $list) {
            if (in_array($lowerUnitString, $list)) {
                return $key;
            }
        }

        throw new Exception("Unrecognized unit \"{$unitString}\"");
    }
}
