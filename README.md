[![Latest Stable Version](https://poser.pugx.org/chrisullyott/php-filesize/v/stable)](https://packagist.org/packages/chrisullyott/php-filesize)
[![Total Downloads](https://poser.pugx.org/chrisullyott/php-filesize/downloads)](https://packagist.org/packages/chrisullyott/php-filesize)

# php-filesize

A flexible library for calculating file sizes and converting between units.

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

The second argument specifies decimal precision (default is 2).

```php
echo $size->as('TB', 3); // 0.488
```

Use `asAuto()` to simply get a user-friendly string.

```php
$size = new FileSize('1234522678.12 KB');

echo $size->asAuto(); // '1.15 TB'
```

### Modify the size

To make changes, use `add()`, `subtract()`, `multiplyBy()`, and `divideBy()`. A variety of file size strings are supported here as well.

```php
$size = new FileSize('4 GB');

$size->add('2G')
     ->subtract('1 gigabytes')
     ->multiplyBy(4)
     ->divideBy(2);

echo $size->asAuto(); // '10.00 GB'
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

echo $size->asAuto(); // '10.92 MB'
```

### Contribute

Did this library help? Let me know!
