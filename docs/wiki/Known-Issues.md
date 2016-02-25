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

And an form with a missing form tag:

```html
<input type="text" name="foo" data-reuse-submitted-value="true" />
<input type="text" name="baz" data-filters="stringtrim" />
```

Renders as:

```html
<input type="text" name="foo" />
```

I haven't figured this out yet, but it is most likely an issue in the DOMDocument or the way it is being used.
