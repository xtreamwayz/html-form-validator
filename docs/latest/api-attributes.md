---
title: API attributes
type: project
layout: page
project: html-form-validator
version: v1
---

## Special Attributes

### name and data-input-name

The name is required to link validation messages and request data.

```html
<input type="email" name="email_address" />
<div data-input-name="email_address" />
```

### data-reuse-submitted-value

Reuse the submitted value and inject it as a value.

```html
<input
  type="text"
  name="username"
  data-reuse-submitted-value="true"
  value="xtreamwayz"
/>
```

### data-filters

Apply filters to the submitted value. Multiple
[standard filters](http://framework.laminas.com/manual/current/en/modules/laminas.filter.set.html)
can be used, separated by a vertical bar. Options can be set with `{key:value,min:2,max:140}`.
The attribute will be removed before rendering the form, including any sensitive options.

```html
<input type="text" name="username" value="" data-filters="stringtrim|alpha" />
```

### data-validators

Add extra validators. Multiple
[standard validators](http://framework.laminas.com/manual/current/en/modules/laminas.validator.set.html)
can be used, separated by a vertical bar. Options can be set with `{key:value,min:2,max:140}`.
The attribute will be removed before rendering the form, including any sensitive options.

```html
<input
  type="text"
  name="username"
  value=""
  data-validators="stringlength{min:2,max:140}|validator{key:val,foo:bar}|notempty"
/>
```

### checked

The checked content attribute is a boolean attribute that gives the default checkedness of the input element. When the
checked content attribute is added, if the control does not have dirty checkedness, the user agent must set the
checkedness of the element to true; when the checked content attribute is removed, if the control does not have dirty
checkedness, the user agent must set the checkedness of the element to false.

### disabled

The disabled content attribute is a boolean attribute.

Constraint validation: If an element is disabled, it is barred from constraint validation.

### list

The list attribute is used to identify an element that lists predefined options suggested to the user.

If present, its value must be the ID of a datalist element in the same document.

The **suggestions source element** is the first element in the document in tree order to have an ID equal to the value
of the list attribute, if that element is a datalist element. If there is no list attribute, or if there is no element
with that ID, or if the first element with that ID is not a datalist element, then there is no suggestions source
element.

```html
<input type="text" name="browser" list="browsers" />
<datalist id="browsers">
  <option value="Edge" />
  <option value="Firefox" />
  <option value="Chrome" />
  <option value="Opera" />
  <option value="Safari" />
</datalist>
```

### max

If the element has a max attribute, and the result of applying the algorithm to convert a string to a number to the
value of the max attribute is a number, then that number is the element's maximum; otherwise, if the type attribute's
current state defines a default maximum, then that is the maximum; otherwise, the element has no maximum.

### min

If the element has a min attribute, and the result of applying the algorithm to convert a string to a number to the
value of the min attribute is a number, then that number is the element's minimum; otherwise, if the type attribute's
current state defines a default minimum, then that is the minimum; otherwise, the element has no minimum.

The min attribute also defines the step base.

### step

The step attribute indicates the granularity that is expected (and required) of the value or values, by limiting the
allowed values. The section that defines the type attribute's current state also defines the default step, the step
scale factor, and in some cases the default step base, which are used in processing the attribute.

### maxlength

If the input element has a maximum allowed value length, then the code-unit length of the value of the element's
value attribute must be equal to or less than the element's maximum allowed value length.

### minlength

If the input element has a minimum allowed value length, then the code-unit length of the value of the element's
value attribute must be equal to or more than the element's maximum allowed value length.

### multiple

The multiple attribute is a boolean attribute that indicates whether the user is to be allowed to specify more than one
value.

### pattern

The pattern attribute specifies a regular expression against which the control's value, or, when the multiple attribute
applies and is set, the control's values, are to be checked.

If specified, the attribute's value must match the JavaScript Pattern production.

If an input element has a pattern attribute specified, and the attribute's value, when compiled as a JavaScript regular
expression with only the "u" flag specified, compiles successfully, then the resulting regular expression is the
element's compiled pattern regular expression. If the element has no such attribute, or if the value doesn't compile
successfully, then the element has no compiled pattern regular expression. [ECMA262]

### readonly

The readonly attribute is a boolean attribute that controls whether or not the user can edit the form control. When
specified, the element is not mutable.

### required

### aria-required

The required attribute is a boolean attribute. When specified, the element is required.

The required attribute triggers the not empty validation.

```html
<input type="email" name="email_address" required />
<input type="email" name="email_address" required="required" />
<input type="email" name="email_address" aria-required="true" />
```
