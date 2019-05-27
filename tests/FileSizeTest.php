<?php

/**
 * Tests for FileSize.
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */

namespace FileSize;

class FileSizeTest extends \PHPUnit_Framework_TestCase
{
    public function testBytes()
    {
        $size = new FileSize('128974848');

        $this->assertSame($size->as('B'), 128974848);
    }

    public function testAdd()
    {
        $size = new FileSize('123 megabytes');
        $size->add('150 KiB');

        $this->assertSame($size->as('B'), 129128448);
    }

    public function testSubtract()
    {
        $size = new FileSize('123M');
        $size->subtract('150 kilobytes');

        $this->assertSame($size->as('B'), 128821248);
    }

    public function testMultiply()
    {
        $size = new FileSize('100 m');
        $size->multiply(7.5);

        $this->assertSame($size->as('GB', 2), 0.73);
    }

    public function testDivide()
    {
        $size = new FileSize('300K');
        $size->divide(2);

        $this->assertSame($size->as('KB'), 150.0);
    }

    public function testConvertUp()
    {
        $size = new FileSize('123456789 TB');

        $this->assertSame($size->as('exabytes', 2), 5.74);
    }

    public function testConvertDown()
    {
        $size = new FileSize('1 Gigabyte');

        $this->assertSame($size->as('B'), 1073741824);
    }

    public function testNoConvert()
    {
        $size = new FileSize('525 Gigabytes');

        $this->assertSame($size->as('GB'), 525.0);
    }

    public function testAuto1()
    {
        $size = new FileSize('1234522678.12 KB');

        $this->assertSame($size->asAuto(), '1.15 TB');
    }

    public function testAuto2()
    {
        $size = new FileSize('1.2345 KB');
        $size->multiply(0.333);

        $this->assertSame($size->asAuto(), '422 B');
    }
}
