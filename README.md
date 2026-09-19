# Laravel Postal Code Validation

Adds postal code validation to Laravel, based on [Google's libaddressinput](https://github.com/google/libaddressinput).

[![Codecov](https://codecov.io/gh/axlon/laravel-postal-code-validation/graph/badge.svg?token=nlrmsSD5op)](https://codecov.io/gh/axlon/laravel-postal-code-validation)
[![Downloads](https://img.shields.io/packagist/dt/axlon/laravel-postal-code-validation)](https://packagist.org/packages/axlon/laravel-postal-code-validation)
[![Latest version](https://img.shields.io/packagist/v/axlon/laravel-postal-code-validation)](https://github.com/axlon/laravel-postal-code-validation/releases)
[![License](https://img.shields.io/packagist/l/axlon/laravel-postal-code-validation)](LICENSE.md)

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    - [Available rule](#available-rule)
    - [Rule object](#rule-object)
    - [Adding an error message](#adding-an-error-message)
- [License](#license)
- [Attribution](#attribution)

## Requirements

This package has the following requirements:

- PHP 8.2 or greater
- Laravel 12 or greater

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

## Usage
Postal code validation perfectly integrates into your Laravel application, you can use it just like you would any
framework validation rule.

### Available rules

This package adds the following validation rules:

#### postal_code:foo,bar,...

The field under validation must be a valid postal code in at least one of the given regions. Each argument may be either
an uppercase [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) country code or the name of a field
that contains one. Region codes are case-sensitive.

```php
'postal_code' => 'postal_code:NL,BE'
```

Fields can be referenced using dot notation:

```php
'shipping.country' => 'nullable|string|size:2|uppercase',
'shipping.postal_code' => 'postal_code:shipping.country'
```

> [!IMPORTANT]
> Validation fails when no arguments resolve to a valid region code.

### Rule object

This package also provides a `PostalCode` rule object, which allows for fluent rule building:

```php
'postal_code' => [
    PostalCode::of(['NL', 'BE']),
],
```

It accepts the same arguments as the `postal_code` rule does.

### Adding an error message

Add the following line to `lang/{locale}/validation.php`:

```php
'postal_code' => 'Your message here.',
```

The following placeholders are automatically filled for you:

| Placeholder | Description                                             |
|-------------|---------------------------------------------------------|
| :attribute  | The name of the field that was under validation         |
| :regions    | The regions that were validated against (e.g. `NL, BE`) |

> [!IMPORTANT]
> The `:regions` placeholder will be empty when referenced fields do not contain any country codes.

## License

This software is licenced under the [MIT license](LICENSE.md).

## Attribution

This software contains data derived from Google's Address Validation Metadata. The original data is provided
by [Google](https://github.com/google/libaddressinput) and is licensed under
the [CC-BY 4.0 license](https://creativecommons.org/licenses/by/4.0/).

### Modifications

- Only data relevant to postal code validation is included
- Regular expressions found in the data are adjusted to be compatible with PHP's regex engine
- The resulting data is used to generate PHP files
