# Laravel Postal Code Validation

Adds postal code validation to Laravel, based on [Google's libaddressinput](https://github.com/google/libaddressinput).

[![Codecov](https://codecov.io/gh/axlon/laravel-postal-code-validation/graph/badge.svg?token=nlrmsSD5op)](https://codecov.io/gh/axlon/laravel-postal-code-validation)
[![Downloads](https://img.shields.io/packagist/dt/axlon/laravel-postal-code-validation)](https://packagist.org/packages/axlon/laravel-postal-code-validation)
[![Latest version](https://img.shields.io/packagist/v/axlon/laravel-postal-code-validation)](https://github.com/axlon/laravel-postal-code-validation/releases)
[![License](https://img.shields.io/packagist/l/axlon/laravel-postal-code-validation)](LICENSE.md)

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    - [Available rules](#available-rules)
    - [Fluent API](#fluent-api)
    - [Adding an error message](#adding-an-error-message)
    - [Manually validating](#manually-validating)
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
'postal_code' => 'postal_code:NL,DE,FR,BE'
```

#### postal_code_with:foo,bar,...
The field under validation must be a postal code in at least one of the countries in the given fields _only if_ at least
one of the specified fields is present.

```php
'billing.country' => 'required|string|max:2',
...
'shipping.country' => 'nullable|string|max:2',
'shipping.postal_code' => 'postal_code_with:billing.country,shipping.country'
```

### Fluent API
If you prefer using a fluent object style over string based rules, that's also available:

```php
'postal_code' => [
    PostalCode::for('NL')->or('BE'),
],
```

The same goes for the `postal_code_with` rule:

```php
'billing.country' => 'required|string|max:2',
...
'shipping.country' => 'nullable|string|max:2',
'shipping.postal_code' => [
    PostalCode::with('billing.country')->or('shipping.country')
],
```

### Adding an error message
To add a meaningful error message, add the following lines to `resources/lang/{your language}/validation.php`:

```php
'postal_code' => 'Your message here',
'postal_code_with' => 'Your message here',
```

The following placeholders will be automatically filled for you:

Placeholder | Description
------------|------------
:attribute  | The name of the field that was under validation
:countries  | The countries that were validated against (e.g. `NL, BE`)*
:examples   | Examples of allowed postal codes (e.g. `1234 AB, 4000`)*

*The `:countries` and `:examples` placeholders may be empty if no valid countries are passed.

### Manually validating
If you want to validate postal codes manually outside of Laravel's validation system, you can call the validator
directly, like so:

```php
PostalCodes::passes($country, $postalCode); // returns a boolean
```

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
