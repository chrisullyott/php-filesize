<?php

/**
 * A class for calculating file sizes and converting between units.
 */

namespace FileSize;

class FileSize
{
    /**
     * The number of bytes in this filesize.
     *
     * @var int
     */
    private $bytes;

    /**
     * A list of user filesize units previously mapped.
     *
     * @var array
     */
    private $unitCache = [];

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
     * A regex pattern for matching size strings, such as:
     *
     * - 10k
     * - 123 MB
     * - 1 gigabytes
     */
    const SIZE_STRING_PATTERN = '/^([0-9\.]+)\s*?([A-Za-z]+)$/';

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
        $this->bytes = self::byteFormat($this->bytes * $n);

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
     * @param  string $unitString Unit such as 'B', 'KB', etc
     * @param  int    $precision Round to this many decimal places
     * @return float|int
     */
    public function as($unitString, $precision = 2)
    {
        $toUnit = $this->lookupUnit($unitString);

        return $this->convert($this->bytes, 'B', $toUnit, $precision);
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
    private function lookupUnit($unitString)
    {
        if (isset($this->unitCache[$unitString])) {
            return $this->unitCache[$unitString];
        }

        $lowerUnitString = strtolower($unitString);

        foreach (self::$unitMap as $key => $list) {
            if (in_array($lowerUnitString, $list)) {
                $this->unitCache[$unitString] = $key;
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

        if (is_numeric($sizeString)) {
            return $this->parseNumericString($sizeString);
        }

        return $this->parseNonNumericString($sizeString);
    }

    /**
     * Parse a numeric size string (bytes) into its parts (value, 'B'). Numeric
     * strings are expected to be a byte count, so decimal-point values would throw
     * an Exception.
     *
     * @param  string $string Numeric such as '1000'
     * @return object
     */
    private function parseNumericString($string)
    {
        $intVal = intval($string);
        $floatVal = floatval($string);

        if ($floatVal == $intVal) {
            return (object) ['value' => $intVal, 'unit' => 'B'];
        }

        throw new Exception("Missing unit for float \"{$floatVal}\"");
    }

    /**
     * Parse a size string into its parts (value, unit).
     *
     * @param string $string Such as '100 MB'
     * @return object
     */
    private function parseNonNumericString($string)
    {
        preg_match(self::SIZE_STRING_PATTERN, $string, $matches);

        if (count($matches) === 3) {
            $value = floatval($matches[1]);
            $unit = $this->lookupUnit($matches[2]);
            return (object) ['value' => $value, 'unit' => $unit];
        }

        throw new Exception("Could not parse \"{$string}\"");
    }

    /**
     * Change the filesize unit measurement using known units.
     *
     * @param  int    $size      The current size
     * @param  string $fromUnit  The current unit
     * @param  string $toUnit    The desired unit
     * @param  int    $precision Round to this many decimal places
     * @return float|int
     */
    private function convert($size, $fromUnit, $toUnit, $precision = null)
    {
        if ($fromUnit !== $toUnit) {
            $index1 = array_search($fromUnit, array_keys(self::$unitMap));
            $index2 = array_search($toUnit, array_keys(self::$unitMap));
            $size = (float) $size * self::byteFactor($index1 - $index2);
        }

        // For bytes, return a rounded integer.
        if ($toUnit === 'B') {
            return self::byteFormat($size);
        }

        return $precision ? round($size, $precision) : $size;
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
     * Format a numeric string into a byte count (integer).
     *
     * @param  string $number A numeric string or float
     * @return int
     */
    private static function byteFormat($number)
    {
        return (int) ceil($number);
    }
}
