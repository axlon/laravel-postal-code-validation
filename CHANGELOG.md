# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 4.0.0 (Unreleased)

### Fixed

- Fixed a `TypeError` when a `postal_code_with` field holds a value that is not a string

### Removed

- Laravel 11 and below are no longer supported
- Lumen is no longer supported
- The `postal_code_for` rule has been removed, use `postal_code_with` instead
- The `PostalCodes` facade has been removed
- Overriding validation patterns is no longer supported
- Validating outside of Laravel's validator is no longer supported
