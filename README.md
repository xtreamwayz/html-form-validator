# html-form-validator

[![Build Status](https://travis-ci.org/xtreamwayz/html-form-validator.svg?branch=master)](https://travis-ci.org/xtreamwayz/html-form-validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/?branch=master)

**NOTE: This is a proof of concept. Use at your own risk!**

---

As challenged by a [tweet](https://twitter.com/Ocramius/status/680817040429592576), this should validate a html form.

It's pretty crazy what you have to do to get a form build. Create a lot of php classes for elements, validation,
etc. So why not build a html5 form and use the standard element attributes to generate the validator and filters.
Together with some powerful html compliant data attributes you can create forms, validation and filters in one place.

A prototype can be seen in action over [here](https://github.com/xtreamwayz/xtreamwayz.com/blob/master/src/App/Action/ContactAction.php).

## Installation

```bash
$ composer require xtreamwayz/html-form-validator
```

## How does it work?

1. **Load the html form into the FormFactory.**
    - The FormFactory automatically creates default validators and filters for all input elements.
    - The FormFactory creates additional validators and filters set by you inside the form with specific data
    attributes.
    - The FormFactory optionally injects default data into the form input elements.
2. **Validate the form against submitted data.**
3. **Render the form.**
    - The FormFactory optionally injects filtered submitted data into the input elements.
    - The FormFactory optionally adds error messages next to the input elements.

## Element attributes

### name / data-input-name="name" *(required)*

The name is required to link validation messages and request data.

```html
<input type="email" name="email_address" />
```

### required / aria-required="true"

The required attribute triggers the not empty validation.

```html
<input type="email" name="email_address" required />
<input type="email" name="email_address" required="required" />
<input type="email" name="email_address" aria-required="true" />
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

```html
<form action="/" method="post">
    <input type="checkbox" name="checkbox" value="value" />

    <input type="color" name="color" />

    <input type="date" name="date" />

    <input type="datetime-local" name="datetime-local" />

    <input type="email" name="email" data-validator-use-mx-check="true" />

    <input type="file" name="file" />

    <input list="browsers" name="browser" />
    <datalist id="browsers">
        <option value="Edge" />
        <option value="Firefox" />
        <option value="Chrome" />
        <option value="Opera" />
        <option value="Safari" />
    </datalist>

    <input type="month" name="month" />

    <input type="number" name="number" min="1" max="5" />

    <input type="password" name="password" required />
    <input type="password" name="password-confirm" required data-validators="identical{token:password}" />

    <input type="radio" name="gender" value="male" /> Male<br />
    <input type="radio" name="gender" value="female" /> Female<br />
    <input type="radio" name="gender" value="other" /> Other

    <input type="range" name="range" min="1" max="10" step="2" />

    <input type="tel" name="tel" data-country="es" />

    <input type="text" name="name" />

    <input type="time" name="time" />

    <input type="url" name="url" />

    <input type="week" name="week" />

    <select name="car">
        <option value="volvo">Volvo</option>
        <option value="saab">Saab</option>
        <option value="mercedes">Mercedes</option>
        <option value="audi">Audi</option>
    </select>

    <textarea name="textarea"></textarea>
</form>
```

## Examples

This is a basic contact form. A lot more examples can be found in the
[test/Fixtures](https://github.com/xtreamwayz/html-form-validator/tree/master/test/Fixtures) dir.

```php
$htmlForm = <<<'HTML'
<form action="{{ path() }}" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-control-label" for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Your name" required
                       data-reuse-submitted-value="true" data-filters="striptags|stringtrim"
                       class="form-control" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-control-label" for="email">Email address</label>
                <input type="email" id="email" name="email" placeholder="Your email address" required
                       data-reuse-submitted-value="true" data-filters="striptags|stringtrim"
                       class="form-control" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-control-label" for="subject">Subject</label>
        <input type="text" id="subject" name="subject" placeholder="Subject" required
               data-reuse-submitted-value="true" data-filters="striptags|stringtrim"
               class="form-control" />
    </div>

    <div class="form-group">
        <label class="form-control-label" for="body">Message</label>
        <textarea id="body" name="body" rows="5" required
                  data-reuse-submitted-value="true" data-filters="stringtrim"
                  class="form-control" placeholder="Message"></textarea>
    </div>

    <input type="hidden" name="token" value="{{ csrf-token }}"
           data-validators="identical{token:{{ csrf-token }}}" required />

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
HTML;

// Create form validator from a twig rendered form template
$form = FormFactory::fromHtml($template->render($htmlForm, [
    'csrf-token' => '123456'
]));

$_POST['name'] = 'John Doe';
$_POST['email'] = 'john.doe@example.com';
$_POST['subject'] = 'Subject of message';
$_POST['body'] = 'ow are you doing.';

// Validate form and return form validation result object
$result = $form->validate($_POST);

// Inject error messages and filtered values from the result object
echo $form->asString($result);
```

## Resources
- https://www.w3.org/wiki/HTML/Elements/input
- http://www.w3schools.com/html/html_form_elements.asp
- http://www.w3schools.com/tags/tag_input.asp
