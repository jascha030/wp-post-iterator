# WordPress Post Iterator

A simple adapter, extending WordPress' `\WP_Query` class while implementing PHP's core `Iterator` interface.

Built mainly for, but not limited to usage with the [twig](https://github.com/twigphp/Twig) templating language for PHP.

## Getting started

## Prerequisites

* Php `^8.0`
* Composer `^2.2` (Not required, but preferred)

### Installation

Installation instructions, with _examples like the one below_.

```shell
composer require jascha030/composer-template
```

## Usage

Require the `vendor/autoload.php` file either in your main plugin file, or functions.php, depending on what you are
building.

### The most effective way to use it

The simplest and most effective way to use this package is by using the `PostCollection` class. It's based around Php's
IteratorAggregate interface and inspired by (e.g.) Laravel's Collection class and Symfony's Finder and LazyIterator
classes.

It provides three static methods to create instances from various types of arguments.

```php
<?php

/**
 * Create instance from any arguments you would pass to a WP_Query.
 */
public static function fromQueryArgs(array $args, bool $keyByPostId = false): PostCollection

/**
 * Create instance from an existing WP_Query instance.
 */
public static function fromQuery(WP_Query $query, bool $keyByPostId = false): PostCollection

/**
 * Create instance inside a globally accessible post loop.
 */
public static function fromLoop(bool $keyByPostId = false): PostCollection
```

### Examples using PostCollection

```php
<?php

// Require Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

$args = [
    'post_type' => 'post',
    'posts_per_page' => -1,
    'post_status' => 'publish'
];

// Create from arguments you would pass to a WP_Query instance (Recommended).
$fromArgs = \Jascha030\WpPostIterator\PostCollection::fromQueryArgs($args);

// Or from an existing instance.
$query = new WP_Query($args);
$fromQuery = \Jascha030\WpPostIterator\PostCollection::fromQuery($query);
```

The point of this class is you are able to loop through it, like you would with an array, only it is more memory
friendly than an actual array.

```php
<?php

$collection = \Jascha030\WpPostIterator\PostCollection::fromQueryArgs($args);

foreach($collection as $post) {
    echo "<h1>{$post->post_title}</h1>";
    
    echo "<p>{$post->post_content}</p>";
}

```

### The main implementation

```php
<?php

/**
 * Simple implementation, extends WP_Query and uses its methods to implement the Iterator interface.
 */
class PostIterator extends \WP_Query implements \Iterator
{
    public function __construct($query = '')

    /**
     * Tells the `key` method whether we should return the post ID when called.
     * If so, this doesn't necessarily mean the posts would be ordered ID.
     */
    final public function keyByPostId(bool $enabled = true): \Iterator

    public function current(): ?\WP_Post

    public function next()

    public function key(): ?int

    public function valid(): bool

    public function rewind(): void
}
```

### Adapter

If you want to create an iterator for an existing `WP_Query` instance use the `PostIteratorAdapter` class. It uses
the `PostIteratorTrait`.

```php
<?php

class PostIteratorAdapter implements \Iterator
{
    use PostIteratorTrait;

    public function __construct(WP_Query $query)

    /**
     * Provide a WP_Query object to defer our calls to.
     */
    private function getQuery(): WP_Query
    
    /**
     * Tells the `key` method whether we should return the post ID when called.
     * If so, this doesn't necessarily mean the posts would be ordered ID.
     */
    private function keyedByPostIds(): bool

    final public function keyByPostId(bool $enabled = true): \Iterator
}

```

### Alternative

If you want to use another class that implements this functionality without sacrificing an existing inheritance chain,
you can use the `PostIteratorTrait` instead.

It provides all the Iterator methods as shown below, it only requires you to implement two methods as shown below:

```php
/**
 * Provide a WP_Query object to defer our calls to.
 */
abstract private function getQuery(): WP_Query;

/**
 * Tells the `key` method whether we should return the post ID when called.
 * If so, this doesn't necessarily mean the posts would be ordered ID.
 */
abstract private function keyedByPostIds(): bool;
```

## Development

Below is some info on development, (so feel free to fork this repo).

### Code style & Formatting

A code style configuration for `friendsofphp/php-cs-fixer` is included, defined in `.php-cs-fixer.dist.php`. By default,
it includes the `PSR-1` and `PSR-12` presets. You can customize or add rules in `.php-cs-fixer.dist.php`.

To use php-cs-fixer without having it necessarily installed globally, a composer script command is also included to
format php code using the provided config file and the vendor binary of php-cs-fixer.

Run php-cs-fixer

```shell
composer run format
```

The above command is an alias, alternatively you can use

```sh 
composer run php-cs-fixer
```

## License

### GPL-2.0+

    jascha030/wp-post-iterator - Adapter for WordPress' WP_Query, implementing Iterator interface.

    Copyright (C) 2022  Jascha van Aalst.
    
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

[Link to the full license provided.](https://github.com/jascha030/wp-post-iterator/blob/master/LICENSE.md)