## name / data-input-name="name" *(required)*

The name is required to link validation messages and request data.

```html
<input type="email" name="email_address" />
```

## required / aria-required="true"

The required attribute triggers the not empty validation.

```html
<input type="email" name="email_address" required />
<input type="email" name="email_address" required="required" />
<input type="email" name="email_address" aria-required="true" />
```

## data-reuse-submitted-value

Reuse the submitted value and inject it as a value.

```html
<input type="text" name="username" data-reuse-submitted-value="true" value="xtreamwayz" />
```

## data-filters

Apply filters to the submitted value. Multiple
[standard filters](http://framework.zend.com/manual/current/en/modules/zend.filter.set.html)
can be used, separated by a vertical bar. Options can be set with ``{key:value,min:2,max:140}``.
The attribute will be removed before rendering the form, including any sensitive options.

```html
<input type="text" name="username" value="" data-filters="stringtrim|alpha" />
```

## data-validators

Add extra validators. Multiple
[standard validators](http://framework.zend.com/manual/current/en/modules/zend.validator.set.html)
can be used, separated by a vertical bar. Options can be set with ``{key:value,min:2,max:140}``.
The attribute will be removed before rendering the form, including any sensitive options.

```html
<input type="text" name="username" value=""
       data-validators="stringlength{min:2,max:140}|validator{key:val,foo:bar}|notempty" />
```

## Custom validation

Sometimes you need to validate javascript generated from fields. This is easy with the ``data-input-name`` and
``data-validators`` attributes.

```html
<div class="form-group g-recaptcha" aria-required="true"
     data-input-name="g-recaptcha-response" data-validators="recaptcha{key:{{ recaptcha_priv_key }}}"
     data-sitekey="{{ recaptcha_pub_key }}" data-theme="light"></div>
```
