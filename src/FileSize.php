<?php

/**
 * A class for calculating file sizes and converting between units.
 */

namespace ChrisUllyott\FileSize;

class FileSize
{
    /**
     * The number of bytes in this filesize.
     *
     * @var int
     */
    private $bytes;

    /**
     * A UnitMapper object.
     *
     * @var UnitMapper
     */
    private $unitMapper;

    /**
     * Constructor.
     *
     * @param string $sizeString Such as '100 MB'
     */
    public function __construct($sizeString = null)
    {
        $this->unitMapper = new UnitMapper();

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
        return $this->convert($this->bytes, 'B', $unitString, $precision);
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
        $units = array_keys(UnitMap::$map);
        $unit = $units[$factor];

        if ($unit === 'B') {
            return "{$value} B";
        }

        return sprintf("%.{$precision}f {$unit}", $value);
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
        $size = SizeStringParser::parse($sizeString);

        return $this->convert($size->value, $size->unit, 'B');
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
        $fromUnit = $this->unitMapper->lookup($fromUnit);
        $toUnit = $this->unitMapper->lookup($toUnit);

        if ($fromUnit !== $toUnit) {
            $index1 = array_search($fromUnit, array_keys(UnitMap::$map));
            $index2 = array_search($toUnit, array_keys(UnitMap::$map));
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
