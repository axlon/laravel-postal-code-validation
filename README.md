# laravel-postal-code-validation
Worldwide postal code validation for Laravel

<p align="center">
    <a href="https://travis-ci.org/axlon/laravel-postal-code-validation"><img src="https://travis-ci.org/axlon/laravel-postal-code-validation.svg?branch=master"></a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation"><img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/downloads"></a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation"><img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/version"></a>
    <a href="https://scrutinizer-ci.com/g/axlon/laravel-postal-code-validation"><img src="https://scrutinizer-ci.com/g/axlon/laravel-postal-code-validation/badges/coverage.png?b=master"></a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation"><img src="https://poser.pugx.org/axlon/laravel-postal-code-validation/license"></a>
</p>



## Usage
Postal code validation perfectly integrates into your Laravel application, you can use it just like you would any
framework validation rule.

### Using the rule as a string
You can call the rule as part of your validation string, the rule expects at least one country code
([ISO 3166-1 alpha 2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)) to validate against.

```php
$this->validate($request, [
    'postal_code' => 'postal_code:NL,BE',
]);
```

### Using the rule directly
If you prefer a more object-like fluent style, that's available too:

```php
$this->validate($request, [
    'postal_code' => [
        PostalCode::forCountry('NL')->andCountry('BE'),
    ],
]);
```

### Country code from request
If you want to validate a postal code against a country code that's passed in the same request, that's also possible.
Simply put the name of the request variable instead of a country code (dot notation is supported).

```php
$this->validate($request, [
    'delivery.country' => 'string|size:2|required',
    'delivery.postal_code' => 'postal_code:delivery.country|required',
]);
```

### Adding an error message
To add an error message your users will be able to understand, open `resources/lang/{your language}/validation.php` and
add the following line to it:

```php
'postal_code' => 'The :attribute field must be a valid postal code of format :format.',
```

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

### Laravel 5.5+
If you are running Laravel 5.5 or higher, package discovery will automatically register the package for you after
running the Composer command.

### Laravel 5.4 and below
If you are running a Laravel version lower than 5.5, register the package by adding the service provider to the
providers array in your `config/app.php` file:

```php
'providers' => [
   ...
   Axlon\PostalCodeValidation\ValidationServiceProvider::class,
   ...
],
```

#### A note on Laravel 5.0
Version 1.3.0 dropped support for Laravel 5.0. If you want to use this package with Laravel 5.0, target the 1.2.x 
family.

### Lumen
If you are running Lumen, register the package by adding the following line to your `bootstrap/app.php` file:

```php
$app->register(Axlon\PostalCodeValidation\ValidationServiceProvider::class);
```

## Special thanks
Special thanks to [sirprize](https://github.com/sirprize), the author of the underlying postal code validation library.
