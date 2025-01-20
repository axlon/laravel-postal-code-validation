# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres
to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 4.0.0

### Changed

- Country codes in incorrect casing are no longer accepted
- Validation now passes on `null`. Add `required` where appropriate to enforce presence
- Validation based on a country field is now done via the `postal_code` rule

### Removed

- Support for Lumen
- Support for Laravel 9.x and below
- Support for PHP 8.0 and below
- Custom error replacers `:countries` and `:examples`
- Manual validation through facade
- Validation overriding
- Validation rule `postal_code_with`, functionality has been merged into `postal_code`
