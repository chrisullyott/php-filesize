<?php

namespace ChrisUllyott\FileSize;

/**
 * Matches arbitrary strings to unit map keys, such as 'Megabytes' => 'MB'.
 */
class UnitMapper
{
    /**
     * A store of previously mapped unit strings.
     *
     * @var array
     */
    private $mappedStrings = [];

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

        if (isset($this->mappedStrings[$unitString])) {
            return $this->mappedStrings[$unitString];
        }

        $sanitizedString = self::sanitizeUnitString($unitString);

        foreach (UnitMap::$map as $key => $list) {
            if (in_array($sanitizedString, $list)) {
                $this->mappedStrings[$unitString] = $key;
                return $key;
            }
        }

        throw new Exception("Unrecognized unit \"{$unitString}\"");
    }

    /**
     * Look up a map index number from a key.
     *
     * @param  string $key Such as 'MB'
     * @return int
     */
    public function indexFromKey($key)
    {
        return array_search($key, array_keys(UnitMap::$map));
    }

    /**
     * Look up a map key from an index number.
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
