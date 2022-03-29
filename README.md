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

Require the `vendor/autoload.php` file either in your main plugin file, or functions.php, depending on what you are building.

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

This composer package is an open-sourced software licensed under
the [MIT License](https://github.com/jascha030/wp-post-iterator/blob/master/LICENSE.md)
