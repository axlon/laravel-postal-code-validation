# Changelog

## [v2.0.0](https://github.com/axlon/laravel-postal-code-validation/tree/v2.0.0) (TBD)
- Changed `postal_code` rule to no longer accept request inputs as arguments (**breaking change**)
- Changed `PostalCode` rule class methods (**breaking change**)
- Added internal validation engine (**potentially breaking change**)
- Added `postal_code_for` rule, which accepts request inputs as arguments
- Added support for referencing request array parameters (e.g. `addresses.*.country`) for Laravel 5.4 and up

## [v1.4.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.4.1) (2019-04-27)
- Fixed replacer being empty if empty input was passed

## [v1.4.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.4.0) (2019-04-13)
- Added error message replacer for `:countries` and `:formats` placeholders

## [v1.3.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.3.1) (2019-04-04)
- Lowered PHP requirement from 7.1.3 to 7.1.0
- Removed exception thrown when an invalid country is passed, instead validation now fails

## [v1.3.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.3.0) (2019-02-27)
- Added Laravel 5.8 support
- Dropped Laravel 5.0 support (due to PHPUnit 8 compatibility issues)
- Increased minimum required PHP version from 7.0.0 to 7.1.3 (due to PHPUnit 8 compatibility issues)

## [v1.2.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.2.1) (2019-02-11)
- Fixed an error when null was passed to the validator
- Added doc blocks
- Added Scrutinizer and StyleCI integration

## [v1.2.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.2.0) (2018-12-29)
- Other fields in the request can now be used as country code to check against
- Removed useless files from dist

## [v1.1.1](https://github.com/axlon/laravel-postal-code-validation/tree/v1.1.1) (2018-12-27)
- Fixed PHPUnit issues on older versions of Laravel (the [issues](https://travis-ci.org/axlon/laravel-postal-code-validation/jobs/472731322) didn't affect the package outside of CI)

## [v1.1.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.1.0) (2018-12-27)
- Country codes are no longer case sensitive
- Documentation improvements
- Minor code improvements

## [v1.0.0](https://github.com/axlon/laravel-postal-code-validation/tree/v1.0.0) (2018-12-07)
- Initial release
