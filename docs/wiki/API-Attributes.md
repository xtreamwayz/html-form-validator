## Special Attributes

### name
### data-input-name

The name is required to link validation messages and request data.

```html
<input type="email" name="email_address" />
```

### data-reuse-submitted-value

Reuse the submitted value and inject it as a value.

```html
<input type="text" name="username" data-reuse-submitted-value="true" value="xtreamwayz" />
```

### data-filters

Apply filters to the submitted value. Multiple
[standard filters](http://framework.zend.com/manual/current/en/modules/zend.filter.set.html)
can be used, separated by a vertical bar. Options can be set with ``{key:value,min:2,max:140}``.
The attribute will be removed before rendering the form, including any sensitive options.

```html
<input type="text" name="username" value="" data-filters="stringtrim|alpha" />
```

### data-validators

Add extra validators. Multiple
[standard validators](http://framework.zend.com/manual/current/en/modules/zend.validator.set.html)
can be used, separated by a vertical bar. Options can be set with ``{key:value,min:2,max:140}``.
The attribute will be removed before rendering the form, including any sensitive options.

```html
<input type="text" name="username" value=""
       data-validators="stringlength{min:2,max:140}|validator{key:val,foo:bar}|notempty" />
```

### checked

### disabled

### list

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

### maxlength

### min

### minlength

### multiple

### pattern

### readonly

### required
### aria-required

The required attribute triggers the not empty validation.

```html
<input type="email" name="email_address" required />
<input type="email" name="email_address" required="required" />
<input type="email" name="email_address" aria-required="true" />
```

### step
