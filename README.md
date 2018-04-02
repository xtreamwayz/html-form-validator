# html-form-validator

[![Build Status](https://travis-ci.org/xtreamlabs/html-form-validator.svg?branch=master)](https://travis-ci.org/xtreamwayz/html-form-validator)
[![Code Coverage](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xtreamwayz/html-form-validator/?branch=master)
[![Packagist](https://img.shields.io/packagist/v/xtreamwayz/html-form-validator.svg)](https://packagist.org/packages/xtreamwayz/html-form-validator)
[![Packagist](https://img.shields.io/packagist/vpre/xtreamwayz/html-form-validator.svg)](https://packagist.org/packages/xtreamwayz/html-form-validator)

As challenged by a [tweet](https://twitter.com/Ocramius/status/680817040429592576), this library extracts validation
rules and filters from a html form and validates submitted user data against it.

It's pretty crazy what you have to do to get a form build in frameworks. Create a lot of php classes for elements,
validation, etc. So why not build a html form and use the standard element attributes to extract the validation rules
and filters. Together with some powerful html compliant data attributes you can create forms, customize validation
rules and filters in one place.

## Installation

```bash
$ composer require xtreamwayz/html-form-validator
```

## How does it work?

1. **Load the html form into the FormFactory and create a new Form instance**

    ```php
    $form = (new FormFactory())->fromHtml($htmlForm, $defaultValues);
    ```

    - The FormFactory creates and returns a Form instance with a new `Zend\InputFilter\Factory` and
      `Zend\InputFilter\InputFilter`.
    - The Form automatically creates default validators and filters for all input elements.
    - The Form extracts additional custom validation rules and filters from the form.
    - The Form optionally injects default data into the form input elements.

2. **Validate the form against submitted data**

    ```php
    $result = $form->validate($_POST);
    ```

    Under the hood it uses [zend-inputfilter](https://docs.zendframework.com/zend-inputfilter/) which makes all its
    [validators](https://docs.zendframework.com/zend-validator/set/) and
    [filters](https://docs.zendframework.com/zend-filter/standard-filters/) available to you.

3. **Render the form**

    ```php
    echo $form->asString($validationResult);
    ```

    Before rendering, the FormFactory removes any data validation attributes used to instantiate custom validation
    (e.g. `data-validators`, `data-filters`). This also removes possible sensitive data that was used to setup
    the validators.

    The `$validationResult` is optional and triggers the following tasks:
    - The FormFactory injects filtered submitted data into the input elements.
    - The FormFactory adds error messages next to the input elements.
    - The FormFactory sets the `aria-invalid="true"` attribute for invalid input elements.
    - The FormFactory adds the bootstrap `has-danger` css class to the parent element.

## Setting a default InputFilter Factory

Sometimes you need custom filters or validators. To register those, a `Zend\InputFilter\Factory` can be used and
injected into the FormFactory. Or use the included `InputFilterFactory` to set this up for you from this config:

```php
return = [
    'zend-inputfilter' => [
        'validators' => [
            // Attach custom validators or override standard validators
            'invokables' => [
                'recaptcha' => RecaptchaValidator::class,
            ],
        ],
        'filters'    => [
            // Attach custom filters or override standard filters
            'invokables' => [],
        ],
    ],
];
```

## Re-usable InputFilters

Still want to use a html form instead of generating it with complicated classes, but you want to reuse the validation
part? We got you covered. The `FormFactory` and `Form` accepts `Zend\InputFilter\InputFilterInterface`s so they can be
re-used everywhere in your app. The `Form` only creates new filters and validators for named input elements that do not
exist yet in the injected InputFilter.

```php
$form = (new FormFactory())->fromHtml($htmlForm, $defaultValues, $userInputFilter);
```

## Documentation

Documentation is available in the [wiki](https://github.com/xtreamwayz/html-form-validator/wiki). There are even
examples for [Zend Expressive](https://github.com/xtreamwayz/html-form-validator/wiki/Example-Zend-Expressive-Action)
and [Symfony](https://github.com/xtreamwayz/html-form-validator/wiki/Example-Symfony-Action).

Pull requests for documentation can be made against the source files in [docs/wiki](docs/wiki).

## Examples

More examples can be found in the [wiki](https://github.com/xtreamwayz/html-form-validator/wiki) and
[test/Fixtures](https://github.com/xtreamwayz/html-form-validator/tree/master/test/Fixtures) dir.

```php
// Basic contact form

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
$form = (new FormFactory())->fromHtml($template->render($htmlForm, [
    'csrf-token' => '123456'
]));

$_POST['name'] = 'Barney Stinsons';
$_POST['email'] = 'barney.stinsons@example.com';
$_POST['subject'] = 'Hi';
$_POST['body'] = 'It is going to be Legen-Wait For It... DARY! LEGENDARY!';

// Validate form and return form validation result object
$result = $form->validate($_POST);

// Check validation result
if ($result->isValid()) {
    $data = $result->getValues();
    // Process data ...
} else {
    // Inject error messages and filtered values from the result object
    echo $form->asString($result);
}
```
