# html-form-validator

*This is just a proof of concept!*

---

As challenged by a [tweet](https://twitter.com/Ocramius/status/680817040429592576), this should validate a html form.

It's pretty crazy what you have to do to get a form build. Create a lot of php classes for elements, validation,
etc. So why not build a html5 form and use the standard element attributes to generate the validator and filters.
Together with some powerful html compliant data attributes you can create forms, validation and filters in one place.

A prototype can be seen in action over [here](https://github.com/xtreamwayz/xtreamwayz.com/blob/master/src/App/Action/ContactAction.php).

## Element attributes

### type *(required)*

The type triggers the predefined validators.

```html
<input type="email" />
```

### name / data-input-name="name" *(required)*

The name is required to link validation messages and request data.

```html
<input type="email" name="email" />
```

### required / aria-required="true"

The required attribute triggers the not empty validation.

```html
<input type="email" name="email" required />
<input type="email" name="email" required="required" />
<input type="email" name="email" aria-required="true" />
```

### data-reuse-submitted-value

Reuse the submitted value and inject it as a value.

```html
<input type="text" name="username" data-reuse-submitted-value="true" value="submitted-user-name" />
```

### data-filters

Apply filters to the submitted value. Multiple
[standard filters](http://framework.zend.com/manual/current/en/modules/zend.filter.set.html)
can be used, separated by a vertical bar. Options can be set with ``{key:value,min:2,max:140}``.

```html
<input type="text" name="username" value="" data-filters="stringtrim|alpha" />
```

### data-validators

Add extra validators. Multiple
[standard validators](http://framework.zend.com/manual/current/en/modules/zend.validator.set.html)
can be used, separated by a vertical bar. Options can be set with ``{key:value,min:2,max:140}``.

```html
<input type="text" name="username" value=""
       data-validators="stringlength{min:2,max:140}|validator{key:val,foo:bar}|notempty" />
```

### Custom validation

Sometimes you need to validate javascript generated from fields. This is easy with the ``data-input-name`` and
``data-validators`` attributes.

```html
<div class="form-group g-recaptcha" aria-required="true"
     data-input-name="g-recaptcha-response" data-validators="recaptcha{key:{{ recaptcha_priv_key }}}"
     data-sitekey="{{ recaptcha_pub_key }}" data-theme="light"></div>
```

## Element types

The form validator detects HTML5 form elements and adds default validators depending on the used attributes.

### Element: input [type="text"]

No default validators.

```html
<input type="text" name="firstName" value="" />
```

### Element: input [type="email"]

Default email validator.

```html
<input type="email" name="email" value="" data-validator-use-mx-check="true" />
```

- Attribute: *data-validator-use-mx-check*

  Use mx check to validate the domain.

### Element: input [type="number"]

The validator that is applied depends on if ``min`` and / or ``max`` is set.

```html
<input type="number" name="rating" value="" min="1" max="10" />
```

- Attribute: *min && max*

  The submitted value must be between the set min and max.

- Attribute: *min*

  The submitted value must be equal or greater than the set min.

- Attribute: *max*

  The submitted value must be equal or lower than the set max.

## Example

```php
$htmlForm = <<<'HTML'
<form action="%s" method="post">
    <label for="email">Email:</label>
    <input
        type="email"
        id="email"
        name="email"
        value=""
        aria-describedby="email-description"
        data-reuse-submitted-value="true"
        data-validator="email-address"
        required="required"
    />
    <span id="email-description" class="help">Enter a valid email address</span>
    <input
        type="number"
        id="intNumber"
        name="intNumber"
        value=""
        min="1"
        max="20"
        data-reuse-submitted-value="true"
        data-validator="between"
    />
    <input
        type="text"
        name="username"
        value=""
        data-reuse-submitted-value="true"
        data-filters="stringtrim,alpha"
    />
    <input type="submit"/>
</form>
HTML;

$form = FormFactory::fromHtml($htmlForm);

$_POST['email'] = 'test@localhost';
$_POST['intNumber'] = 22;
$_POST['username'] = ' xtreamwayz 22 ';
var_dump($form->validate($_POST)); // returns form validation result VO

echo $form->asString();
```

## Resources
- http://www.w3schools.com/html/html_form_elements.asp
- http://www.w3schools.com/tags/tag_input.asp
