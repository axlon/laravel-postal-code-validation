# laravel-postal-code-validation
Postal code validation for Laravel

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

## Usage
Postal code validation perfectly integrates into your Laravel application, you can use it just like you would any
framework validation rule.

### Using the rule as a string
You can call the rule as part of your validation string (see example below). The rule expects at least one country code
to validate against.

```php
$this->validate($request, [
    'postal_code' => 'postal_code:NL,BE',
]);
```

### Using the rule directly
If you prefer a more object-like style, that's available too (see example below).

```php
$this->validate($request, [
    'postal_code' => [
        PostalCode::forCountry('NL')->andCountry('BE'),
    ],
]);
```

### Adding a readable error message
To add an error message your users will be able to understand, open `resources/lang/{your language}/validation.php` and
add the following line to it:

```php
'postal_code' => 'The :attribute field must be a valid postal code.',
```

## Special thanks
Special thanks to [sirprize](https://github.com/sirprize), the author of the underlying postal code validation library.

## Contributing
If your country code is not yet supported and you want to add support for it, please do so by making a pull request on
the [underlying library](https://github.com/sirprize/postal-code-validator).
