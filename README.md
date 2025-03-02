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
    - [Fluent API](#fluent-api)
    - [Adding an error message](#adding-an-error-message)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Requirements
This package has the following requirements:

- Laravel 10 or greater
- PHP 8.1 or greater

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
