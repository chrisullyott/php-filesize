<?php

/**
 * Tests for FileSize.
 */

use ChrisUllyott\FileSize;

class FileSizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test a numeric string input.
     */
    public function testBytes()
    {
        $size = new FileSize('128974848');

        $this->assertSame($size->as('B'), 128974848);
    }

    /**
     * Test that "partial bytes" are rounded up.
     */
    public function testBytesRounding()
    {
        $size = new FileSize('99.7 bytes');

        $this->assertSame($size->as('B'), 100);
    }

    /**
     * Test #add.
     */
    public function testAdd()
    {
        $size = new FileSize('123 megabytes');
        $size->add('150 KiB');

        $this->assertSame($size->as('B'), 129128448);
    }

    /**
     * Test #add with a negative value.
     */
    public function testAddNegative()
    {
        $size = new FileSize('10MB');
        $size->add('-20MB');

        $this->assertSame($size->asAuto(), '-10 MB');
    }

    /**
     * Test #subtract.
     */
    public function testSubtract()
    {
        $size = new FileSize('123M');
        $size->subtract('150 kilobytes');

        $this->assertSame($size->as('B'), 128821248);
    }

    /**
     * Test #subtract with a negative value.
     */
    public function testSubtractNegative()
    {
        $size = new FileSize('10MB');
        $size->subtract('-20MB');

        $this->assertSame($size->asAuto(), '30 MB');
    }

    /**
     * Test adding an array of items.
     */
    public function testAddMany()
    {
        $size = new FileSize();
        $size->add(['50mb', '140mb', '1.2mb']);

        $this->assertSame($size->as('MB'), 191.2);
    }

    /**
     * Test #multiplyBy.
     */
    public function testMultiplyBy()
    {
        $size = new FileSize('425.51 m');
        $size->multiplyBy(9.125);

        $this->assertSame($size->as('GB'), 3.79);
    }

    /**
     * Test #divideBy.
     */
    public function testDivideBy()
    {
        $size = new FileSize('300K');
        $size->divideBy(2);

        $this->assertSame($size->as('KiB'), (float) 150);
    }

    /**
     * Test upward unit conversion.
     */
    public function testConvertUp()
    {
        $size = new FileSize('123456789 TB');

        $this->assertSame($size->as('exabytes'), 5.74);
    }

    /**
     * Test downward unit conversion.
     */
    public function testConvertDown()
    {
        $size = new FileSize('1 Gigabyte');

        $this->assertSame($size->as('B'), 1073741824);
    }

    /**
     * Test when the unit has not changed.
     */
    public function testNoConvert()
    {
        $size = new FileSize('525 Gibibytes');

        $this->assertSame($size->as('GB'), (float) 525);
    }

    /**
     * Test auto-formatting for a small value.
     */
    public function testAutoSmall()
    {
        $size = new FileSize('1.2345 KB');
        $size->divideBy(3);

        $this->assertSame($size->asAuto(), '422 B');
    }

    /**
     * Test auto-formatting for a large value.
     */
    public function testAutoLarge()
    {
        $size = new FileSize('1234522678.12 KB');

        $this->assertSame($size->asAuto(), '1.15 TB');
    }

    /**
     * Test the rounding in auto-formatting (should not leave trailing zeros).
     */
    public function testAutoRounding()
    {
        $size = new FileSize('158.1983 mb');

        $this->assertSame($size->asAuto(), '158.2 MB');
    }

    /**
     * Test a decimal base conversion.
     */
    public function testDecimalBase()
    {
        $size = new FileSize(10921134, 10);

        $this->assertSame($size->asAuto(), '10.92 MB');
    }
}
