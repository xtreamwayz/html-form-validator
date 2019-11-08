---
title: "Known issues"
type: "project"
layout: "page"
project: "html-form-validator"
version: "1.0"
---

## The form doesn't render all elements

Check if you use a valid form. A valid form includes the form tag.

```html
<form action="/" method="post">
    <input type="text" name="foo" data-reuse-submitted-value="true" />
    <input type="text" name="baz" data-filters="stringtrim" />
</form>
```

Renders as:

```html
<form action="/" method="post">
    <input type="text" name="foo" />
    <input type="text" name="baz" />
</form>
```

And a form with a missing form tag:

```html
<input type="text" name="foo" data-reuse-submitted-value="true" />
<input type="text" name="baz" data-filters="stringtrim" />
```

Renders as:

```html
<input type="text" name="foo" />
```
