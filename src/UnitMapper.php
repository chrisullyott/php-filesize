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
    public function lookup($unitString)
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
