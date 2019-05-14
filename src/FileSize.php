<?php

/**
 * A class for calculating file sizes and converting between units.
 */

class FileSize
{
    /**
     * The number of bytes in this filesize.
     *
     * @var int
     */
    private $bytes;

    /**
     * A mapping of filesize units to lowercase strings.
     *
     * @var array
     */
    private static $unitMap = [
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

    /**
     * Constructor.
     *
     * @param string $sizeString Such as '100 MB'
     */
    public function __construct($sizeString = null)
    {
        $this->bytes = $sizeString ? $this->stringToBytes($sizeString) : 0;
    }

    /**
     * Add to this filesize.
     *
     * @param string $sizeString Such as '100 MB'
     * @return self
     */
    public function add($sizeString)
    {
        $this->bytes += $this->stringToBytes($sizeString);

        return $this;
    }

    /**
     * Subtract from this filesize, stopping at 0 bytes.
     *
     * @param string $sizeString Such as '100 MB'
     * @return self
     */
    public function subtract($sizeString)
    {
        $bytesToSubtract = $this->stringToBytes($sizeString);

        if ($bytesToSubtract < $this->bytes) {
            $this->bytes -= $bytesToSubtract;
        } else {
            $this->bytes = 0;
        }

        return $this;
    }

    /**
     * Multiply the filesize by a number.
     *
     * @param int|float $n A number
     * @return self
     */
    public function multiply($n)
    {
        $this->bytes = (int) ceil($this->bytes * $n);

        return $this;
    }

    /**
     * Divide the filesize by a number.
     *
     * @param  int|float $n A number
     * @return self
     */
    public function divide($n)
    {
        return $this->multiply(1 / $n);
    }

    /**
     * Get the filesize in a given unit.
     *
     * @param  string $newUnit Unit such as 'B', 'KB', etc
     * @param  int    $precision Round to this many decimal places
     * @return float|int
     */
    public function as($newUnit, $precision = 2)
    {
        return $this->convert($this->bytes, 'B', $newUnit, $precision);
    }

    /**
     * Get the filesize in a human-friendly string.
     *
     * @param  int $precision Round to this many decimal places
     * @return string
     */
    public function asAuto($precision = 2)
    {
        if (!is_int($precision)) {
            throw new Exception('First argument must be an integer');
        }

        $factor = floor((strlen($this->bytes) - 1) / 3);
        $value = $this->bytes / self::byteFactor($factor);
        $units = array_keys(self::$unitMap);
        $unit = $units[$factor];

        if ($unit === 'B') {
            return "{$value} B";
        }

        return sprintf("%.{$precision}f {$unit}", $value);
    }

    /**
     * Get the standardized unit from a unit string, like 'GB' from 'gigabytes'.
     *
     * @param  string The unit string
     * @return string
     */
    private function getUnit($unitString)
    {
        $lowerUnitString = strtolower($unitString);

        foreach (self::$unitMap as $key => $list) {
            if (in_array($lowerUnitString, $list)) {
                return $key;
            }
        }

        throw new Exception("Unrecognized unit \"{$unitString}\"");
    }

    /**
     * Get the byte count from an arbitrary size string. Numeric entries will be
     * considered a count of bytes.
     *
     * @param string $sizeString Such as '100 MB'
     * @return int
     */
    private function stringToBytes($sizeString)
    {
        $size = $this->parseSizeString($sizeString);

        return $this->convert($size->value, $size->unit, 'B');
    }

    /**
     * Parse a size string into its parts (value, unit).
     *
     * @param string $sizeString Such as '100 MB'
     * @return object
     */
    private function parseSizeString($sizeString)
    {
        $sizeString = trim($sizeString);
        $sizeObject = new stdClass();

        if (is_numeric($sizeString)) {
            $sizeObject->value = $sizeString;
            $sizeObject->unit = 'B';
        } else {
            preg_match('/^.*?([0-9\.]+)\s*?([a-zA-Z]+).*$/', $sizeString, $matches);
            $sizeObject->value = $matches[1];
            $sizeObject->unit = $matches[2];
        }

        return $sizeObject;
    }

    /**
     * Get the number of bytes per factor. (2^10 = 1,024; 2^20 = 1,048,576...)
     *
     * @param  int $factor
     * @return int
     */
    private static function byteFactor($factor)
    {
        return 2 ** (10 * $factor);
    }

    /**
     * Change the filesize unit measurement using arbitrary unit strings.
     *
     * @param  int    $size      The current size
     * @param  string $fromUnit  The current unit
     * @param  string $toUnit    The desired unit
     * @param  int    $precision Round to this many decimal places
     * @return float|int
     */
    private function convert($size, $fromUnit, $toUnit, $precision = null)
    {
        $fromUnit = $this->getUnit($fromUnit);
        $toUnit = $this->getUnit($toUnit);

        if ($fromUnit !== $toUnit) {
            $index1 = array_search($fromUnit, array_keys(self::$unitMap));
            $index2 = array_search($toUnit, array_keys(self::$unitMap));
            $size = (float) $size * self::byteFactor($index1 - $index2);
        }

        // Return an integer for bytes only.
        if ($toUnit === 'B') {
            return (int) $size;
        }

        return $precision ? round($size, $precision) : $size;
    }
}
