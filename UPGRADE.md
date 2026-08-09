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
