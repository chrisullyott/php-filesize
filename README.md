[![Latest Stable Version](https://poser.pugx.org/chrisullyott/php-filesize/v/stable)](https://packagist.org/packages/chrisullyott/php-filesize)
[![Total Downloads](https://poser.pugx.org/chrisullyott/php-filesize/downloads)](https://packagist.org/packages/chrisullyott/php-filesize)

# php-filesize

A simple, flexible class for calculating binary file sizes and converting between units.

### Installation

Include in your project, or, install with [Composer](https://getcomposer.org/):

```bash
$ composer require chrisullyott/php-filesize
```

### Instantiate

A `FileSize` object, both on creation and within its methods, understands just about any expression of data size in bytes. You may instantiate it with a size, or leave it initially empty.

```php
$size = new FileSize('500 GB');
```

### Convert between units

Use `as()` to export the size in another format.

```php
echo $size->as('MB'); // 512000
```

The second parameter specifies decimal precision (default is 2).

```php
echo $size->as('TB', 3); // 0.488
```

Use `asAuto()` to simply get a user-friendly string.

```php
$size = new FileSize('1234522678.12 KB');
echo $size->asAuto(); // '1.15 TB'
```

### Add, subtract, multiply, divide

To make changes, use `add()`, `subtract()`, `multiply()`, and `divide()`. A variety of file size strings are supported here as well.

```php
$size = new FileSize('1 GB');

$size->add('142.3M')
     ->add('1 terabyte')
     ->subtract('40.1 KiB')
     ->multiply(0.5)
     ->divide(3);

echo $size->asAuto(); // '170.86 GB'
```

### Details

- Since this is in binary, remember that `500 MB` translates to `0.49 GB` and not exactly `0.5 GB` as you'd expect in the less accurate decimal system. See [Wikipedia](https://en.wikipedia.org/wiki/Binary_prefix).
- Exporting bytes returns an `int`, otherwise a `float`.

### Contribute

Did this library help? Let me know!
