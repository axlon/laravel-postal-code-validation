# laravel-postal-code-validation
Worldwide postal code validation for Laravel, based on Google's Address Data Service.

<p align="center">
    <a href="https://travis-ci.org/axlon/laravel-postal-code-validation">
        <img src="https://travis-ci.org/axlon/laravel-postal-code-validation.svg?branch=master">
    </a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation">
        <img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/downloads">
    </a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation">
        <img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/version">
    </a>
    <a href="https://scrutinizer-ci.com/g/axlon/laravel-postal-code-validation">
        <img src="https://scrutinizer-ci.com/g/axlon/laravel-postal-code-validation/badges/coverage.png?b=master">
    </a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation">
        <img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/license">
    </a>
</p>

- [Requirements](#requirements)
- [Installation](#installation)
    - [Laravel 5.5+](#laravel-55)
    - [Laravel 5.4](#laravel-54)
    - [Lumen](#lumen)
- [Usage](#usage)
    - [Available rules](#available-rules)
    - [Fluent API](#fluent-api)
    - [Adding an error message](#adding-an-error-message)
    - [Manually validating](#manually-validating)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Requirements
This package has the following requirements:

- PHP 7.1 or higher
- Laravel (or Lumen) 5.4 or higher

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

### Laravel 5.5+
If you use Laravel 5.5 or higher, that's it, continue to the [usage](#usage) section.

### Laravel 5.4
If you're using an older version of Laravel, register the package's service provider to your application. You can do
this by adding the following line to your `config/app.php` file:

```php
'providers' => [
   ...
   Axlon\PostalCodeValidation\ValidationServiceProvider::class,
   ...
],
```

### Lumen
If you are using Lumen, register the package by adding the following line to your `bootstrap/app.php` file:

```php
$app->register(Axlon\PostalCodeValidation\ValidationServiceProvider::class);
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

#### postal_code_for:foo,bar,...
The field under validation must be a postal code in at least one of the countries in the given fields _only if_ at least
one of the specified fields is present.

```php
'billing.country' => 'required|string|max:2',
...
'shipping.country' => 'nullable|string|max:2',
'shipping.postal_code' => 'postal_code_for:billing.country,shipping.country'
```

### Fluent API
If you prefer using a fluent object style over string based rules, that's also available:

```php
'postal_code' => [
    PostalCode::forCountry('NL')->or('BE'),
],
```

The same goes for the `postal_code_for` rule:

```php
'billing.country' => 'required|string|max:2',
...
'shipping.country' => 'nullable|string|max:2',
'shipping.postal_code' => [
    PostalCode::forInput('billing.country')->or('shipping.country')
],
```

### Adding an error message
To add a meaningful error message, add the following lines to `resources/lang/{your language}/validation.php`:

```php
'postal_code' => 'Your message here',
'postal_code_for' => 'Your message here',
```

The following placeholders will be automatically filled for you:

Placeholder | Description
------------|------------
:attribute  | The name of the field that was under validation
:codes      | The countries that were validated against (e.g. `NL, BE`)
:examples   | Examples of allowed postal codes (e.g. `1234 AB, 4000`)
:fields     | The referenced field names (`postal_code_for` only)

>The `:codes` and `:examples` placeholders may be empty if no valid countries are passed.

### Manually validating
If you want to validate postal codes manually outside of Laravel's validation system, you can call the validator
directly, like so:

```php
$validator = app('validator.postal_codes');
$validator->validate($countryCode, $postalCode); // returns a boolean
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
