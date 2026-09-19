# Upgrade guide

This document lists every change that requires action when upgrading between major versions. For a full list of changes,
see the [changelog](CHANGELOG.md).

## Upgrading to 4.0 from 3.x

### The `postal_code_for` and `postal_code_with` rules have been removed

**Likelihood of impact: high**

The `postal_code` rule now accepts both literal region codes and fields containing region codes. Replace
`postal_code_with` and `postal_code_for` with `postal_code`:

```diff
-'postal_code' => 'postal_code_with:country',
+'postal_code' => 'postal_code:country',
```

### Region codes must be uppercase

**Likelihood of impact: medium**

Region codes are now case-sensitive and must use uppercase ISO 3166-1 alpha-2 format. This applies both to region codes
written directly in a rule and to values read from referenced fields. Previously, improperly cased codes were
normalized internally:

```diff
-'postal_code' => 'postal_code:nl',
+'postal_code' => 'postal_code:NL',
```

Normalize values from referenced fields before validation, or require uppercase input:

```diff
-'country' => 'required|string|size:2',
+'country' => 'required|string|size:2|uppercase',
```

### The fluent rule builder has been replaced

**Likelihood of impact: medium**

The `PostalCode::for()`, `forCountry()`, `forInput()`, `with()`, and `or()` methods have been removed. Pass all literal
regions or field names to `PostalCode::of()` instead:

```diff
-PostalCode::for('NL')->or('BE')
+PostalCode::of(['NL', 'BE'])

-PostalCode::with('billing.country')->or('shipping.country')
+PostalCode::of(['billing.country', 'shipping.country'])
```

### A referenced region must be resolved for validation to pass

**Likelihood of impact: medium**

The old `postal_code_with` rule passed when all of its referenced fields were missing. The unified `postal_code` rule
fails when none of its parameters resolve to a valid region code. Ensure at least one referenced field contains an
ISO 3166-1 alpha-2 code whenever the postal code is validated, or conditionally exclude the postal code rule.

### Parameterless rules no longer throw an exception

**Likelihood of impact: low**

A parameterless `postal_code` rule now produces a normal validation error instead of throwing an
`InvalidArgumentException`. Handle the validation failure instead of catching that exception. In most cases, supplying
the missing literal region or field name is the appropriate fix.

### The service provider was renamed

**Likelihood of impact: low**

Applications using Laravel package discovery require no changes. If you register the package manually, update the
service provider reference:

```diff
-Axlon\PostalCodeValidation\ValidationServiceProvider::class,
+Axlon\PostalCodeValidation\PostalCodeServiceProvider::class,
```

### The `:countries` message placeholder has been renamed

**Likelihood of impact: medium**

Replace `:countries` with `:regions` in custom `validation.postal_code` messages:

```diff
-'postal_code' => ':attribute must be valid for :countries.',
+'postal_code' => ':attribute must be valid for :regions.',
```

### Postal code examples are no longer available in validation messages

**Likelihood of impact: low**

The `:examples` placeholder has been removed. If a custom `postal_code` validation message uses
it, remove the placeholder or replace it with static wording:

```diff
-'postal_code' => ':attribute must be valid for :regions (for example :examples).',
+'postal_code' => ':attribute must be valid for :regions.',
```

### Validating outside of Laravel's validator is no longer supported

**Likelihood of impact: low**

Postal codes can no longer be validated by hand; the `postal_code` rule is now the only way to validate. Anywhere you
validated manually, run the value through the validator instead:

```diff
-if (PostalCodes::passes($country, $postalCode)) {
-    // ...
-}
+$passes = Validator::make(
+    ['postal_code' => $postalCode],
+    ['postal_code' => PostalCode::of([$country])],
+)->passes();
+
+if ($passes) {
+    // ...
+}
```

### Overriding validation patterns is no longer supported

**Likelihood of impact: low**

If you believe one of the shipped patterns contains a bug, please open
an [issue](https://github.com/axlon/laravel-postal-code-validation/issues/new) so it can be fixed for everyone.
