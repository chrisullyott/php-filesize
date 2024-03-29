[![Latest Stable Version](https://poser.pugx.org/chrisullyott/php-filesize/v/stable)](https://packagist.org/packages/chrisullyott/php-filesize)
[![Total Downloads](https://poser.pugx.org/chrisullyott/php-filesize/downloads)](https://packagist.org/packages/chrisullyott/php-filesize)

# php-filesize

A flexible package for handling file sizes and converting between units.

### Installation

Include in your project, or, install with [Composer](https://getcomposer.org/):

```bash
$ composer require chrisullyott/php-filesize
```

### Instantiate

A `FileSize` object, both on creation and within its methods, understands just about any expression of data size. You may instantiate it with a size, or leave it initially empty.

```php
use ChrisUllyott\FileSize;

$size = new FileSize('500 GB');
```

### Convert between units

Use `as()` to export the size in another format.

```php
echo $size->as('MB'); // 512000
```

A variety of file size strings are supported here as well.

```php
echo $size->as('megabytes'); // 512000
```

The second argument specifies decimal precision (default is `2`).

```php
echo $size->as('TB', 3); // 0.488
```

### User-friendly formatting

Use `asAuto()` to get a user-friendly string:

```php
$size = new FileSize('1234522678.12 KB');

echo $size->asAuto(); // '1.15 TB'
```

Optionally, `asAuto()` also provides a decimal precision.

```php
$size = new FileSize('1234522678.12 KB');

echo $size->asAuto(5); // '1.14974 TB'
```

Or, simply `echo` the object for the same functionality:

```php
echo $size; // '1.15 TB'
```

### Modify the size

To make changes, use `add()`, `subtract()`, `multiplyBy()`, and `divideBy()`.

```php
$size = new FileSize('4 GB');

$size->add('2G')
     ->subtract('1 gigabytes')
     ->multiplyBy(4)
     ->divideBy(2);

echo $size; // '10.00 GB'
```

Negative values are supported. In the case below, 1.2 megabytes are subtracted:

```php
$size->add('-1.2mb');
```

You may also use `add()` and `subtract()` with an array of values:

```php
$size->add(['50mb', '140mb', '1.2mb']);
```

### Number base

The second argument of the constructor is the number base, which accepts either `2` (binary) or `10` (decimal). We use binary by default. To handle sizes in decimal:

```php
$size = new FileSize(10921134, 10);

echo $size; // '10.92 MB'
```

### Decimal separator

The third argument of the constructor is the decimal separator, which is a period `.` by default. Here, you can use a comma instead. The chosen decimal separator will be used both to parse numbers properly, and also to format them on output.

```php
$size = new FileSize('1.234.522.678,12 KB', 2, ',');

echo $size; // '1,15 TB'
```
