# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 0.2.0 - TBD

### Added

- [#15](https://github.com/xtreamwayz/html-form-validator/pull/15) adds support for the maxlength attribute for
  specifc elements.
- [#21](https://github.com/xtreamwayz/html-form-validator/pull/21) adds the `aria-invalid="true"` attribute if
  the validation result object is injected into the form renderer: `$form->asString($validationResult)`.
- [#23](https://github.com/xtreamwayz/html-form-validator/pull/23) adds automatic GitHub
  [wiki pages](https://github.com/xtreamwayz/html-form-validator/wiki) generation from the docs.

### Deprecated

Nothing.

### Removed

- [#14](https://github.com/xtreamwayz/html-form-validator/pull/14) removes list and datalist checks since it is a
  global attribute that suggests values, not restrict to a value from the list.

### Fixed

- [#15](https://github.com/xtreamwayz/html-form-validator/pull/15) makes the pattern attribute trigger the regex
  plugin only for specific elements.
- [#21](https://github.com/xtreamwayz/html-form-validator/pull/21) fixes adding multiple error classes.
- [#24](https://github.com/xtreamwayz/html-form-validator/pull/24) fixes adding multiple error messages to elements
  with the same name.

## 0.1.1 - 2016-02-09

### Added

- [#7](https://github.com/xtreamwayz/html-form-validator/pull/7) adds tests.
- [#7](https://github.com/xtreamwayz/html-form-validator/pull/7) adds country data attribute for telephone number
  validation.

### Deprecated

Nothing.

### Removed

Nothing.

### Fixed

- [#7](https://github.com/xtreamwayz/html-form-validator/pull/7) fixes checkbox checking the submitted value.
- [#7](https://github.com/xtreamwayz/html-form-validator/pull/7) fixes input radio type check for valid value.
- [#7](https://github.com/xtreamwayz/html-form-validator/pull/7) adds missing zend-uri dependency.
- [#7](https://github.com/xtreamwayz/html-form-validator/pull/7) fixes range step having min as a base value,
  otherwise it's 0.
- [#7](https://github.com/xtreamwayz/html-form-validator/pull/7) fixes pattern attribute regex.

## 0.1.0 - 2016-02-08

Initial tagged release.

### Added

Everything.

### Deprecated

Nothing.

### Removed

Nothing.

### Fixed

Nothing.
