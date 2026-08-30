# Upgrade guide

This document lists every change that requires action when upgrading between major versions. For a full list of changes,
see the [changelog](CHANGELOG.md).

## Upgrading to 4.0 from 3.x

### The `postal_code_for` rule has been removed

**Likelihood of impact: low**

The undocumented `postal_code_for` rule was an alias of `postal_code_with` and has been removed. Replace any usage of it
with `postal_code_with`, rule parameters are unchanged:

```diff
-'postal_code' => 'postal_code_for:country',
+'postal_code' => 'postal_code_with:country',
```

If you published a `validation.postal_code_for` translation line, rename it to `validation.postal_code_with`.

### Validating outside of Laravel's validator is no longer supported

**Likelihood of impact: low**

Postal codes can no longer be validated by hand; the `postal_code` and `postal_code_with` rules are now the only way to
validate. Anywhere you validated manually, run the value through the validator instead:

```diff
-if (PostalCodes::passes($country, $postalCode)) {
-    // ...
-}
+$passes = Validator::make(
+    ['postal_code' => $postalCode],
+    ['postal_code' => PostalCode::for($country)],
+)->passes();
+
+if ($passes) {
+    // ...
+}
```

### Overriding validation patterns is no longer supported

**Likelihood of impact: low**

While no longer a first-party feature, it is now possible to override both patterns and examples, you may do this by
extending `ConstraintRepository` and `ExampleRepository` respectively. See
the [Laravel docs](https://laravel.com/framework/docs/container#extending-bindings) for more information.

If you believe one of the shipped patterns contains a bug, please open
an [issue](https://github.com/axlon/laravel-postal-code-validation/issues/new) so it can be fixed for everyone.
