# Changelog

## 4.0.0 (Unreleased)

### Added

- The `postal_code` rule now supports area codes as well as input field names

### Changed

- Laravel 12 is now the minimum required version
- PHP 8.2 is now the minimum required version
- The `PostalCode` rule builder API has been simplified

### Removed

- Lumen is no longer supported
- Removed the `PostalCodes` facade
- Removed standalone postal code validation
- Removed validation pattern overrides
- Removed the `postal_code_for` and `postal_code_with` validation rules, use `postal_code` instead
- Removed the `:examples` error message placeholder
