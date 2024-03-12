# Changelog

## [v3.6.0](https://github.com/axlon/laravel-postal-code-validation/tree/v3.6.0) (2024-03-12)

- Add support for Laravel 11

## [v3.5.0](https://github.com/axlon/laravel-postal-code-validation/tree/v3.5.0) (2024-01-24)

- Update Dutch validation pattern
- Update Bahraini validation pattern

## [v3.4.0](https://github.com/axlon/laravel-postal-code-validation/tree/v3.4.0) (2023-02-15)
- Add support for Laravel 10

## [v3.3.0](https://github.com/axlon/laravel-postal-code-validation/tree/v3.3.0) (2022-02-08)
- Add support for Laravel 9

## [v3.2.1](https://github.com/axlon/laravel-postal-code-validation/tree/v3.2.1) (2020-12-15)
- Update Cambodia validation pattern

## [v3.2.0](https://github.com/axlon/laravel-postal-code-validation/tree/v3.2.0) (2020-11-27)
- Added support for PHP 8
- Fixed validation passing prematurely if `postal_code_for` or `postal_code_with` only referenced fields in an array

## [v3.1.3](https://github.com/axlon/laravel-postal-code-validation/tree/v3.1.3) (2020-11-07)
- Fixed validation getting bypassed on `postal_code_for` and `postal_code_with` when only some referenced fields were present

## [v3.1.2](https://github.com/axlon/laravel-postal-code-validation/tree/v3.1.2) (2020-10-25)
- Fixed postal_code_for rule failing when none of the referenced fields were present
- Deprecated the postal_code_for rule
- Added postal_code_with rule, which is a drop-in replacement for postal_code_for
- Added deprecation warnings for upcoming 4.0 release

## [v3.1.1](https://github.com/axlon/laravel-postal-code-validation/tree/v3.1.1) (2020-09-30)
- Fixed error when validation receives null ([#23](https://github.com/axlon/laravel-postal-code-validation/issues/23))

## [v3.1.0](https://github.com/axlon/laravel-postal-code-validation/tree/v3.1.0) (2020-09-09)
- Added support for Laravel 8 ([#21](https://github.com/axlon/laravel-postal-code-validation/pull/21))

## [v3.0.0](https://github.com/axlon/laravel-postal-code-validation/tree/v3.0.0) (2020-08-31)
- Dropped support for PHP 7.1 (**breaking change**)
- Dropped support for Laravel 5.1 - 5.4 (**breaking change**)
- Added support for manually overriding validation patterns
- Added exceptions when no arguments are passed to the validation rules (**breaking change**)
- Added PostalCodes facade
- Renamed `Axlon\PostalCodeValidation\Validator` to `Axlon\PostalCodeValidation\PostalCodeValidator` and changed its methods (**breaking change**)

## [v2.1.1](https://github.com/axlon/laravel-postal-code-validation/tree/v2.1.1) (2020-08-16)
- Update Taiwan validation pattern

## [v2.1.0](https://github.com/axlon/laravel-postal-code-validation/tree/v2.1.0) (2020-03-04)
- Added support for Laravel 7

## [v2.0.2](https://github.com/axlon/laravel-postal-code-validation/tree/v2.0.2) (2020-03-03)
- Fixed validation of countries with complex patterns
[#13](https://github.com/axlon/laravel-postal-code-validation/issues/13)
- Fixed rules not being loaded if the validator was resolved before the service provider was called
- Fixed format generator (dev-only)

## [v2.0.1](https://github.com/axlon/laravel-postal-code-validation/tree/v2.0.1) (2019-09-07)
- Added support for Laravel 6

## [v2.0.0](https://github.com/axlon/laravel-postal-code-validation/tree/v2.0.0) (2019-07-06)
- Updated `postal_code` rule, which longer accepts references to request parameters as arguments (**breaking change**)
- Updated fluent API methods (**breaking change**)
- Removed `:formats` error message placeholder (**breaking change**)
- Replaced the validation engine with an internal engine based on Google's Address Data Service
(**potentially breaking change**)
- Added `postal_code_for` rule, which accepts references to request parameters as arguments
- Added `:examples` error message placeholder
- Added support for referencing request parameters inside an array (for example `addresses.*.country`)
- Added support for referencing request variables while using a custom validator class

## [v1.4.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.4.1) (2019-04-27)
- Fixed replacer being empty if empty input was passed

## [v1.4.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.4.0) (2019-04-13)
- Added error message replacer for `:countries` and `:formats` placeholders

## [v1.3.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.3.1) (2019-04-04)
- Lowered PHP requirement from 7.1.3 to 7.1.0
- Removed exception thrown when an invalid country is passed, instead validation now fails

## [v1.3.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.3.0) (2019-02-27)
- Added Laravel 5.8 support
- Removed Laravel 5.0 support
- Increased minimum required PHP version from 7.0.0 to 7.1.3

## [v1.2.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.2.1) (2019-02-11)
- Fixed an error when null was passed to the validator
- Added doc blocks
- Added Scrutinizer and StyleCI integration

## [v1.2.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.2.0) (2018-12-29)
- Other fields in the request can now be used as country code to check against
- Removed useless files from dist

## [v1.1.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.1.1) (2018-12-27)
- Fixed PHPUnit issues on older versions of Laravel

## [v1.1.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.1.0) (2018-12-27)
- Country codes are no longer case sensitive
- Documentation improvements
- Minor code improvements

## [v1.0.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.0.0) (2018-12-07)
- Initial release
