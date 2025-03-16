# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.0.0]

### Changed

- **Breaking change**: Validation will no longer fail if the field is present but empty. To ensure that the field is
 both present and filled, use the required rule.

### Fixed

- Validating a value that is not a string or null will no longer cause a `TypeError`

### Removed

- Support for Laravel 5.5 - 9.x
- Support for Lumen
- Ability to customize validation for a country
- Manual validation through facade
- Custom error replacers `countries` and `examples`
