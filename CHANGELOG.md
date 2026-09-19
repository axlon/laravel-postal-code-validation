# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 4.0.0 (Unreleased)

### Changed

- The `postal_code` rule now accepts both region codes and fields name as parameters
- Region codes must now use uppercase ISO 3166-1 alpha-2 format
- Validation now fails when no valid region can be resolved from rule parameters
- A parameterless `postal_code` rule now returns a validation error instead of throwing an `InvalidArgumentException`
- The `:countries` message placeholder has been renamed to `:regions`
- The `PostalCode` rule object can now only be created via `PostalCode::of()`, replacing its old builder methods

### Fixed

- Fixed a `TypeError` when a referenced field holds a value that is not a string

### Removed

- Laravel 11 and below are no longer supported
- Lumen is no longer supported
- The `postal_code_for` and `postal_code_with` rules have been removed; use `postal_code` instead
- The `PostalCodes` facade has been removed
- The `:examples` message placeholder has been removed
- Overriding validation patterns is no longer supported
- Validating outside of Laravel's validator is no longer supported
