<?php

/**
 * Easily calculate file sizes and convert between units.
 */

namespace ChrisUllyott;

use ChrisUllyott\FileSize\Math\Math;
use ChrisUllyott\FileSize\UnitMap\UnitMap;
use ChrisUllyott\FileSize\UnitMap\UnitMapper;
use ChrisUllyott\FileSize\Parser\SizeStringParser;

class FileSize
{
    /**
     * The number of bytes in this filesize.
     *
     * @var int
     */
    private $bytes;

    /**
     * The number base.
     *
     * @var int
     */
    private $base;

    /**
     * A UnitMapper object.
     *
     * @var UnitMapper
     */
    private $unitMapper;

    /**
     * Constructor.
     *
     * @param string|int $size Such as '100 MB'
     * @param int $base The number base
     */
    public function __construct($size = null, $base = 2)
    {
        $this->unitMapper = new UnitMapper();

        $this->base = $base;
        $this->bytes = $size ? $this->sizeToBytes($size) : 0;
    }

    /**
     * Get the byte count from an arbitrary size string.
     *
     * @param string|int $size Such as '100 MB'
     * @return int
     */
    private function sizeToBytes($size)
    {
        $object = SizeStringParser::parse($size);

        $value = floatval($object->value);
        $unit = $object->unit ? $object->unit : UnitMap::BYTE;

        return $this->convert($value, $unit, UnitMap::BYTE);
    }

    /**
     * Add one or many filesizes.
     *
     * @param array|string|int $sizes
     * @return self
     */
    public function add($sizes)
    {
        foreach ((array) $sizes as $size) {
            $this->addSize($size);
        }

        return $this;
    }

    /**
     * Subtract one or many filesizes.
     *
     * @param array|string|int $sizes
     * @return self
     */
    public function subtract($sizes)
    {
        foreach ((array) $sizes as $size) {
            $this->subtractSize($size);
        }

        return $this;
    }

    /**
     * Add to this filesize.
     *
     * @param string|int $size Such as '100 MB'
     * @return self
     */
    private function addSize($size)
    {
        $this->bytes += $this->sizeToBytes($size);

        return $this;
    }

    /**
     * Subtract from this filesize.
     *
     * @param string|int $size Such as '100 MB'
     * @return self
     */
    private function subtractSize($size)
    {
        $this->bytes -= $this->sizeToBytes($size);

        return $this;
    }

    /**
     * Multiply the filesize by a number.
     *
     * @param int|float $n A number
     * @return self
     */
    public function multiplyBy($n)
    {
        $this->bytes = self::formatBytes($this->bytes * $n);

        return $this;
    }

    /**
     * Divide the filesize by a number.
     *
     * @param  int|float $n A number
     * @return self
     */
    public function divideBy($n)
    {
        return $this->multiplyBy(1 / $n);
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
        return $this->convert($this->bytes, UnitMap::BYTE, $unitString, $precision);
    }

    /**
     * Get the filesize as a human-friendly string.
     *
     * @param  int $precision Round to this many decimal places
     * @return string
     */
    public function asAuto($precision = 2)
    {
        $factor = Math::factorByBytes($this->bytes);
        $size = $this->bytes / Math::bytesByFactor($factor, $this->base);
        $unit = $this->unitMapper->keyFromIndex($factor);

        return self::formatNumber($size, $precision, $unit);
    }

    /**
     * Print the filesize as a human-friendly string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->asAuto();
    }

    /**
     * Change the filesize unit measurement using arbitrary units.
     *
     * @param  int    $size      The current size
     * @param  string $fromUnit  The current unit
     * @param  string $toUnit    The desired unit
     * @param  int    $precision Round to this many decimal places
     * @return float|int
     */
    private function convert($size, $fromUnit, $toUnit, $precision = null)
    {
        $fromUnit = $this->unitMapper->keyFromString($fromUnit);
        $toUnit = $this->unitMapper->keyFromString($toUnit);

        if ($fromUnit !== $toUnit) {
            $index1 = $this->unitMapper->indexFromKey($fromUnit);
            $index2 = $this->unitMapper->indexFromKey($toUnit);
            $factor = $index1 - $index2;
            $size = (float) $size * Math::bytesByFactor($factor, $this->base);
        }

        if ($toUnit === UnitMap::BYTE) {
            return self::formatBytes($size);
        }

        return self::formatNumber($size, $precision);
    }

    /**
     * Format a numeric string into a byte count (integer).
     *
     * @param  string $number A numeric string or float
     * @return int
     */
    private static function formatBytes($number)
    {
        return (int) ceil($number);
    }

    /**
     * Format a number for output.
     *
     * @param  float|int  $value     The number value
     * @param  int        $precision Round to this many decimal places
     * @param  string     $unit      A unit string to append
     * @return float|string
     */
    private static function formatNumber($value, $precision = null, $unit = null)
    {
        $value = !is_null($precision) ? round($value, $precision) : $value;

        return $unit ? "{$value} {$unit}" : $value;
    }
}
