<?php

/**
 * Tests for FileSize.
 */

use ChrisUllyott\FileSize;
use PHPUnit\Framework\TestCase;

class FileSizeTest extends TestCase
{
    /**
     * @test
     */
    public function bytes_are_returned_as_an_integer()
    {
        $size = new FileSize('128974848');

        $this->assertSame($size->as('B'), 128974848);
    }

    /**
     * @test
     */
    public function partial_bytes_are_rounded_up()
    {
        $size = new FileSize('99.7 bytes');

        $this->assertSame($size->as('B'), 100);
    }

    /**
     * @test
     */
    public function sizes_can_be_added()
    {
        $size = new FileSize('123 megabytes');
        $size->add('150 KiB');

        $this->assertSame($size->as('B'), 129128448);
    }

    /**
     * @test
     */
    public function negative_sizes_can_be_added()
    {
        $size = new FileSize('10 MB');
        $size->add('-20 MB');

        $this->assertSame($size->asAuto(), '-10 MB');
    }

    /**
     * @test
     */
    public function sizes_can_be_subtracted()
    {
        $size = new FileSize('123 M');
        $size->subtract('150 kilobytes');

        $this->assertSame($size->as('B'), 128821248);
    }

    /**
     * @test
     */
    public function negative_sizes_can_be_subtracted()
    {
        $size = new FileSize('10 MB');
        $size->subtract('-20 MB');

        $this->assertSame($size->asAuto(), '30 MB');
    }

    /**
     * @test
     */
    public function arrays_can_be_added()
    {
        $size = new FileSize();
        $size->add(['50mb', '140mb', '1.2mb']);

        $this->assertSame($size->as('MB'), 191.2);
    }

    /**
     * @test
     */
    public function sizes_can_be_multiplied()
    {
        $size = new FileSize('425.51 m');
        $size->multiplyBy(9.125);

        $this->assertSame($size->as('GB'), 3.79);
    }

    /**
     * @test
     */
    public function sizes_can_be_divided()
    {
        $size = new FileSize('300K');
        $size->divideBy(2);

        $this->assertSame($size->as('KiB'), (float) 150);
    }

    /**
     * @test
     */
    public function sizes_can_be_converted_up()
    {
        $size = new FileSize('123456789 TB');

        $this->assertSame($size->as('exabytes'), 5.74);
    }

    /**
     * @test
     */
    public function sizes_can_be_converted_down()
    {
        $size = new FileSize('1 GB');

        $this->assertSame($size->as('megabytes'), (float) 1024);
    }

    /**
     * @test
     */
    public function size_value_is_unchanged_without_conversion()
    {
        $size = new FileSize('525 GB');

        $this->assertSame($size->as('GB'), (float) 525);
    }

    /**
     * @test
     */
    public function friendly_formatting_is_valid_for_small_values()
    {
        $size = new FileSize('1.2345 KB');
        $size->divideBy(3);

        $this->assertSame($size->asAuto(), '422 B');
    }

    /**
     * @test
     */
    public function friendly_formatting_is_valid_for_large_values()
    {
        $size = new FileSize('1234522678.12 KB');

        $this->assertSame($size->asAuto(), '1.15 TB');
    }

    /**
     * @test
     */
    public function base_ten_conversions_are_accurate()
    {
        $size = new FileSize(10921134, 10);

        $this->assertSame($size->asAuto(), '10.92 MB');
    }

    /**
     * @test
     */
    public function custom_decimal_mark_is_supported()
    {
        $size = new FileSize(10921134, 10, ',');

        $this->assertSame($size->asAuto(), '10,92 MB');
    }

    /**
     * @test
     */
    public function custom_decimal_and_thousands_marks_are_supported()
    {
        $size = new FileSize('1.234.522.678,12 KB', 2, ',');

        $this->assertSame($size->asAuto(), '1,15 TB');
    }
}
