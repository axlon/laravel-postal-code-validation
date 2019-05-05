# laravel-postal-code-validation
Worldwide postal code validation for Laravel

<p align="center">
    <a href="https://travis-ci.org/axlon/laravel-postal-code-validation"><img src="https://travis-ci.org/axlon/laravel-postal-code-validation.svg?branch=master"></a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation"><img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/downloads"></a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation"><img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/version"></a>
    <a href="https://scrutinizer-ci.com/g/axlon/laravel-postal-code-validation"><img src="https://scrutinizer-ci.com/g/axlon/laravel-postal-code-validation/badges/coverage.png?b=master"></a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation"><img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/license"></a>
</p>

- [Installation](#installation)
    - [Requirements](#requirements)
    - [Laravel 5.5+](#laravel-55)
    - [Laravel 5.1-5.4](#laravel-51-54)
    - [Lumen](#lumen)
- [Usage](#usage)
    - [Available rules](#available-rules)
    - [Rule objects](#rule-objects)
    - [Adding an error message](#adding-an-error-message)
    - [Manually validating](#manually-validating)

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

### Requirements
This package has the following requirements:

- PHP 7.1 or higher
- Laravel (or Lumen) 5.1 or higher

### Laravel 5.5+
If you use Laravel 5.5 or higher, that's it. You can now use the package, continue to the [usage](#usage) section.

### Laravel 5.1-5.4
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
If you are running Lumen, register the package by adding the following line to your `bootstrap/app.php` file:

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

#### postal_code_with:foo,bar,...
The field under validation must be a postal code in at least one of the countries in the given fields _only if_ at least
one of the specified fields is present.

```php
'billing.country' => 'required|string|max:2',
...
'shipping.country' => 'nullable|string|max:2',
'shipping.postal_code' => 'postal_code_with:billing.country,shipping.country'
```

**Important**: while this rule supports array references (e.g. `postal_code:deliveries.*.country`), this will not work
in Laravel 5.1-5.3 due to framework limitations.

### Fluent API
If you prefer using object based rules, that's also available. You can use the `PostalCode` class to build the rules dynamically
(handy if your country is session based, for example).

```php
session()->put('country', 'US');

...

'postal_code' => [
    PostalCode::for(session()->get('country)),
],
```

The `postal_code_with` rule is also available as an object rule:

```php
'country' => 'string|max:2',
'postal_code' => [
    PostalCode::with('country'),
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
:attribute  | The name of the field that was under validation.
:countries  | The countries that are validated against (e.g. `NL, BE`)*
:formats    | The formats that the field must be (e.g. `#### NN, ####`)*

*The `:countries` and `:formats` placeholders will be empty for the `postal_code_with` rule if no valid country input is
passed.

### Manually validating
If you want to validate postal codes manually outside of Laravel's validation system, you can call the validator
directly, like so:

```php
$validator = $this->app->make('\Axlon\PostalCodeValidation\Validator');
$validator->isValid($countryCode, $postalCode);
```
