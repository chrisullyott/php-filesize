<?php

/**
 * Match arbitrary strings to unit map keys, such as 'Megabytes' => 'MB'.
 */

namespace ChrisUllyott\FileSize\UnitMap;

use ChrisUllyott\FileSize\Exception\FileSizeException;

class UnitMapper
{
    /**
     * A store of unit map keys.
     *
     * @var array
     */
    private $keys = [];

    /**
     * A store of previously mapped unit strings.
     *
     * @var array
     */
    private $cache = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $keyStrings = array_keys(UnitMap::$map);
        $keyIndeces = array_flip($keyStrings);

        $this->keys = [
            'by_index' => $keyStrings,
            'by_string' => $keyIndeces
        ];
    }

    /**
     * Map an arbitrary unit string to a unit map key.
     *
     * @param  string $unitString Such as 'Megabytes'
     * @return string
     */
    public function keyFromString($unitString)
    {
        if ($unitString === UnitMap::BYTE) {
            return UnitMap::BYTE;
        }

        if (isset($this->cache[$unitString])) {
            return $this->cache[$unitString];
        }

        $sanitizedString = self::sanitizeUnitString($unitString);

        foreach (UnitMap::$map as $key => $list) {
            if (in_array($sanitizedString, $list)) {
                $this->cache[$unitString] = $key;
                return $key;
            }
        }

        throw new FileSizeException("Unrecognized unit \"{$unitString}\"");
    }

    /**
     * Look up a map index number from a key.
     *
     * @param  string $key Such as 'MB'
     * @return int
     */
    public function indexFromKey($key)
    {
        return $this->keys['by_string'][$key];
    }

    /**
     * Look up a map key from an index number.
     *
     * @param  int $index An integer
     * @return string
     */
    public function keyFromIndex($index)
    {
        return $this->keys['by_index'][$index];
    }

    /**
     * Sanitize a unit string into a version that can be mapped.
     *
     * @param  string $unitString Such as '100 MB'
     * @return string
     */
    private static function sanitizeUnitString($unitString)
    {
        return str_replace('bytes', 'byte', strtolower($unitString));
    }
}
