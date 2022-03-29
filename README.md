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

### The main implementation

```php
<?php

declare(strict_types=1);

namespace Jascha030\WpPostIterator;

/**
 * Simple implementation, extends WP_Query and uses its methods to implement the Iterator interface.
 */
class PostIterator extends \WP_Query implements \Iterator
{
    private bool $keyByPostId;

    public function __construct($query = '')
    {
        $this->keyByPostId = false;

        parent::__construct($query);
    }

    /**
     * Tells the `key` method whether we should return the post ID when called.
     * If so, this doesn't necessarily mean the posts would be ordered ID.
     */
    final public function keyByPostId(bool $enabled = true): \Iterator
    {
        $this->keyByPostId = $enabled;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function current(): ?\WP_Post
    {
        if (! isset($this->post)) {
            $this->the_post();
        }

        return $this->post;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return $this->next_post();
    }

    /**
     * {@inheritDoc}
     */
    public function key(): ?int
    {
        if (0 === $this->post_count) {
            return null;
        }

        return $this->keyByPostId
            ? $this->current_post
            : $this->post->ID;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->have_posts();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->rewind_posts();
    }
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