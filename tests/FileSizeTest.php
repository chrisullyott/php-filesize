<?php

/**
 * Tests for FileSize.
 */

use ChrisUllyott\FileSize;
use PHPUnit\Framework\TestCase;

class FileSizeTest extends TestCase
{
    /**
     * @test numeric string input.
     */
    public function bytes()
    {
        $size = new FileSize('128974848');

        $this->assertSame($size->as('B'), 128974848);
    }

    /**
     * @test "partial bytes" are rounded up.
     */
    public function bytesRounding()
    {
        $size = new FileSize('99.7 bytes');

        $this->assertSame($size->as('B'), 100);
    }

    /**
     * @test #add.
     */
    public function add()
    {
        $size = new FileSize('123 megabytes');
        $size->add('150 KiB');

        $this->assertSame($size->as('B'), 129128448);
    }

    /**
     * @test #add with a negative value.
     */
    public function addNegative()
    {
        $size = new FileSize('10MB');
        $size->add('-20MB');

        $this->assertSame($size->asAuto(), '-10 MB');
    }

    /**
     * @test #subtract.
     */
    public function subtract()
    {
        $size = new FileSize('123M');
        $size->subtract('150 kilobytes');

        $this->assertSame($size->as('B'), 128821248);
    }

    /**
     * @test #subtract with a negative value.
     */
    public function subtractNegative()
    {
        $size = new FileSize('10MB');
        $size->subtract('-20MB');

        $this->assertSame($size->asAuto(), '30 MB');
    }

    /**
     * @test adding an array of items.
     */
    public function addMany()
    {
        $size = new FileSize();
        $size->add(['50mb', '140mb', '1.2mb']);

        $this->assertSame($size->as('MB'), (float) 191.2);
    }

    /**
     * @test #multiplyBy.
     */
    public function multiplyBy()
    {
        $size = new FileSize('425.51 m');
        $size->multiplyBy(9.125);

        $this->assertSame($size->as('GB'), (float) 3.79);
    }

    /**
     * @test #divideBy.
     */
    public function divideBy()
    {
        $size = new FileSize('300K');
        $size->divideBy(2);

        $this->assertSame($size->as('KiB'), (float) 150);
    }

    /**
     * @test upward unit conversion.
     */
    public function convertUp()
    {
        $size = new FileSize('123456789 TB');

        $this->assertSame($size->as('exabytes'), 5.74);
    }

    /**
     * @test downward unit conversion.
     */
    public function convertDown()
    {
        $size = new FileSize('1 Gigabyte');

        $this->assertSame($size->as('B'), 1073741824);
    }

    /**
     * @test when the unit has not changed.
     */
    public function noConvert()
    {
        $size = new FileSize('525 Gibibytes');

        $this->assertSame($size->as('GB'), (float) 525);
    }

    /**
     * @test auto-formatting for a small value.
     */
    public function autoSmall()
    {
        $size = new FileSize('1.2345 KB');
        $size->divideBy(3);

        $this->assertSame($size->asAuto(), '422 B');
    }

    /**
     * @test auto-formatting for a large value.
     */
    public function autoLarge()
    {
        $size = new FileSize('1234522678.12 KB');

        $this->assertSame($size->asAuto(), '1.15 TB');
    }

    /**
     * @test the rounding in auto-formatting (should not leave trailing zeros).
     */
    public function autoRounding()
    {
        $size = new FileSize('158.1983 mb');

        $this->assertSame($size->asAuto(), '158.2 MB');
    }

    /**
     * @test a decimal base conversion.
     */
    public function testDecimalBase()
    {
        $size = new FileSize(10921134, 10);

        $this->assertSame($size->asAuto(), '10.92 MB');
    }
}
