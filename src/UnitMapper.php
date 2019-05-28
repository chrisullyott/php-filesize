<?php

namespace ChrisUllyott\FileSize;

class UnitMapper
{
    /**
     * A store of previously mapped unit strings.
     *
     * @var array
     */
    private $cache = [];

    /**
     * Map an arbitrary unit string to a key.
     *
     * @param  string $unitString Such as 'Terabyte'
     * @return string
     */
    public function keyFromString($unitString)
    {
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

        throw new Exception("Unrecognized unit \"{$unitString}\"");
    }

    /**
     * Lookup a map index number from a key.
     *
     * @param  string $key Such as 'MB'
     * @return int
     */
    public function indexFromKey($key)
    {
        return array_search($key, array_keys(UnitMap::$map));
    }

    /**
     * Lookup a map key from an index number.
     *
     * @param  int $index An integer
     * @return string
     */
    public function keyFromIndex($index)
    {
        $keys = array_keys(UnitMap::$map);

        return $keys[$index];
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
