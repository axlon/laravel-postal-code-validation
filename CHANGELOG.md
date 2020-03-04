# Changelog

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
