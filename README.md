> [!WARNING]
> This documentation is for version 4.x, which is still in development. For production, use version 3.x and follow the
> [3.x documentation](https://github.com/axlon/laravel-postal-code-validation/blob/3.x/README.md).

# Laravel Postal Code Validation

Adds postal code validation to Laravel, based on [Google's libaddressinput](https://github.com/google/libaddressinput).

[![Tests](https://github.com/axlon/laravel-postal-code-validation/actions/workflows/phpunit.yml/badge.svg)](https://github.com/axlon/laravel-postal-code-validation/actions/workflows/phpunit.yml)
[![Downloads](https://img.shields.io/packagist/dt/axlon/laravel-postal-code-validation)](https://packagist.org/packages/axlon/laravel-postal-code-validation)
[![Latest version](https://img.shields.io/packagist/v/axlon/laravel-postal-code-validation)](https://github.com/axlon/laravel-postal-code-validation/releases)
[![License](https://img.shields.io/packagist/l/axlon/laravel-postal-code-validation)](LICENSE.md)

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    - [Validate a postal code](#validate-a-postal-code)
    - [Validate a postal code against a dynamic area](#validate-a-postal-code-against-a-dynamic-area)
    - [String notation](#string-notation)
    - [Adding an error message](#adding-an-error-message)
- [License](#license)
- [Attribution](#attribution)
    - [Modifications](#modifications)

## Requirements

This package has the following requirements:

- Laravel 12 or greater
- PHP 8.2 or greater

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

Laravel registers the service provider through package discovery. To register it manually, add it to your Laravel
application's `bootstrap/providers.php` file:

```php
return [
    // ...
    Axlon\PostalCodeValidation\PostalCodeServiceProvider::class,
];
```

## Usage

### Validate a postal code

The field under validation must be a valid postal code in at least one of the specified areas. Arguments must be
countries in [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) format:

```php
use Axlon\PostalCodeValidation\Rules\PostalCode;

request()->validate([
    'address.postal_code' => ['required', PostalCode::of(['NL', 'BE'])],
]);
```

### Validate a postal code against a dynamic area

You may also validate a postal code against an area code that is passed in the request:

```php
use Axlon\PostalCodeValidation\Rules\PostalCode;

request()->validate([
    'address.country' => ['required', 'in:NL,BE'],
    'address.postal_code' => ['required', PostalCode::of('address.country')],
]);
```

### String notation

Use `postal_code` with one or more comma-separated parameters. Just like with the object rule, you can mix literal area
codes and input field references:

```diff
request()->validate([
-    'address.postal_code' => ['required', PostalCode::of(['NL', 'BE])],
+    'address.postal_code' => 'required|postal_code:NL,BE',
]);
```

### Adding an error message

To add a meaningful error message, add an error message to `resources/lang/{your language}/validation.php`:

```php
'postal_code' => 'The :attribute must be a valid postal code for :areas.',
```

The following placeholders will be automatically filled for you:

| Placeholder | Description                                           |
|-------------|-------------------------------------------------------|
| :attribute  | The name of the field that was under validation       |
| :areas      | The areas that were validated against (e.g. `NL, BE`) |

## License

This software is licenced under the [MIT license](LICENSE.md).

## Attribution

This software contains data derived from Google's Address Validation Metadata. The original data is provided
by [Google](https://github.com/google/libaddressinput) and is licensed under
the [CC-BY 4.0 license](https://creativecommons.org/licenses/by/4.0/).

### Modifications

- Only data relevant to postal code validation is included
- Regular expressions found in the data are adjusted to be compatible with PHP's regex engine
- The resulting data is converted into PHP resource files
