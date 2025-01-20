# Laravel Postal Code Validation
Worldwide postal code validation for Laravel, based on Google's Address Data Service.

<p align="center">
    <a href="https://github.com/axlon/laravel-postal-code-validation/actions">
        <img src="https://github.com/axlon/laravel-postal-code-validation/workflows/tests/badge.svg" alt="Build status">
    </a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation">
        <img src="https://img.shields.io/packagist/dt/axlon/laravel-postal-code-validation" alt="Downloads">
    </a>
    <a href="https://github.com/axlon/laravel-postal-code-validation/releases">
        <img src="https://img.shields.io/packagist/v/axlon/laravel-postal-code-validation" alt="Latest version">
    </a>
    <a href="LICENSE.md">
        <img src="https://img.shields.io/packagist/l/axlon/laravel-postal-code-validation" alt="License">
    </a>
</p>

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    - [Available rules](#available-rules)
    - [Adding an error message](#adding-an-error-message)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Requirements

This package has the following requirements:

- PHP 8.1 or greater
- Laravel 10 or greater

| Laravel version | Package version                                                         |
|-----------------|-------------------------------------------------------------------------|
| 5.1 - 5.4       | [2.x](https://github.com/axlon/laravel-postal-code-validation/tree/2.x) |
| 5.5 - 9.x       | [3.x](https://github.com/axlon/laravel-postal-code-validation/tree/3.x) |
| 10.x - 11.x     | [4.x](https://github.com/axlon/laravel-postal-code-validation/tree/4.x) |

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

If you have package discovery enabled, that's it, continue to the [usage](#usage) section. If you want to register the
package manually, you can do this by adding the following line to your `config/app.php` file:

```php
'providers' => [
   ...
   Axlon\PostalCodeValidation\ValidationServiceProvider::class,
   ...
],
```

## Usage

Postal code validation perfectly integrates into your Laravel application, you can use it just like you would any
framework validation rule.

### Available rules

This package adds the following validation rules:

#### postal_code:foo,bar,...

The field under validation must be a valid postal code in at least one of the given countries. Arguments must be
countries in [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) format.

```php
Validator::validate($data, [
    'address.postal_code' => 'required|postal_code:NL,BE',
]);
```

Alternatively, you may use an object-oriented approach:

```php
use Axlon\PostalCodeValidation\Rules\PostalCode;

Validator::validate($data, [
    'address.postal_code' => ['required', PostalCode::of('NL', 'BE')],
]);
```

### Adding an error message

To add a meaningful error message, add the following to `resources/lang/{language}/validation.php`:

```php
'postal_code' => ':Attribute is not a valid postal code.',
```

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits
- [Choraimy Kroonstuiver](https://github.com/axlon)
- [All contributors](https://github.com/axlon/laravel-postal-code-validation/contributors)

## License
This open-source software is licenced under the [MIT license](LICENSE.md). This software contains code generated from
Google's Address Data Service, more information on this service can be found
[here](https://github.com/google/libaddressinput/wiki/AddressValidationMetadata).
