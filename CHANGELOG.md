# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 0.4.0 - TBD

### Added

- [#36](https://github.com/xtreamwayz/html-form-validator/pull/36) adds the `FormFactory->validateRequest()` to handle
  PSR-7 requests and reduce boilerplate code needed to build, validate and render forms.
- [#38](https://github.com/xtreamwayz/html-form-validator/pull/38) adds submit button detection.
  ```php
  $validationResult->isClicked('confirm'); // returns boolean;
  $validationResult->isClicked(); // returns name of clicked button or null;
  ```
- [#39](https://github.com/xtreamwayz/html-form-validator/pull/39) changes the order of the inputfilter factory and the
  default values in the constructor. The new way to call the constructor is:
  ```php
  public function __construct($htmlForm, Factory $factory = null, array $defaultValues = []);
  ```

### Deprecated

Nothing.

### Removed

Nothing.

### Fixed

- [#38](https://github.com/xtreamwayz/html-form-validator/pull/38) fixes named submit buttons being detected as a
  validating input value.

## 0.3.0 - 2016-02-22

### Added

- [#27](https://github.com/xtreamwayz/html-form-validator/pull/26) adds the FormFactoryInterface and 
  ValidationResultInterface.
- [#29](https://github.com/xtreamwayz/html-form-validator/pull/29) adds container-interop compatibility. This enables
  custom validators and filters.
- [#29](https://github.com/xtreamwayz/html-form-validator/pull/29) adds the InputFilterFactory which can be used to
  instantiate a Zend\InputFilter\Factory from a container-interop compatible container.
- [#30](https://github.com/xtreamwayz/html-form-validator/pull/30) adds 
    - min, max and step attributes for Datetime input types
    - the multiple attribute on select and email elements
    - file element validation options

### Deprecated

- [#27](https://github.com/xtreamwayz/html-form-validator/pull/26) deprecates: 
    - ValidationResult->getErrorMessages(), use ValidationResult->getMessages() instead.
    - ValidationResult->getRawInputValues(), use ValidationResult->getRawValues() instead.
    - ValidationResult->getValidValues(), use ValidationResult->getValues() instead.

### Removed

Nothing.

### Fixed

- [#30](https://github.com/xtreamwayz/html-form-validator/pull/30) fixes invalid validation regex for the color type.  

## 0.2.0 - 2016-02-15

### Added

- [#15](https://github.com/xtreamwayz/html-form-validator/pull/15) adds support for the maxlength attribute for
  specifc elements.
- [#21](https://github.com/xtreamwayz/html-form-validator/pull/21) adds the `aria-invalid="true"` attribute if
  the validation result object is injected into the form renderer: `$form->asString($validationResult)`.
- [#23](https://github.com/xtreamwayz/html-form-validator/pull/23) adds automatic GitHub
  [wiki pages](https://github.com/xtreamwayz/html-form-validator/wiki) generation from the docs.
- [#26](https://github.com/xtreamwayz/html-form-validator/pull/26) adds default filters confirm the html specs.
- [#26](https://github.com/xtreamwayz/html-form-validator/pull/26) adds support for the minlength attribute.
- [#26](https://github.com/xtreamwayz/html-form-validator/pull/26) adds support for number float validation
  and `step="any"` to disable the step validator.

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
