---
title: Getting started
type: project
layout: page
project: html-form-validator
version: v1
---

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

### 1. Build the form

Create the form from html. Nothing fancy here. Create the form with the form
factory from a html form and optionally default values. Only the first
`<form></form>` element is processed. Following form elements or html code
outside the first form element is ignored.

```php
$form = (new FormFactory())->fromHtml($htmlForm, $defaultValues);
```

- The FormFactory creates and returns a Form instance with a new `Zend\InputFilter\Factory` and
    `Zend\InputFilter\InputFilter`.
- The Form automatically creates default validators and filters for all input elements.
- The Form extracts additional custom validation rules and filters from the form.
- The Form optionally injects default data into the form input elements.

### 2. Validate the form

The easiest way is to use a framework that uses [PSR-7 requests](http://www.php-fig.org/psr/psr-7/).

```php
// Validate PSR-7 request and return a ValidationResponseInterface
// It should only start validation if it was a post and if there are submitted values
$validationResult = $form->validateRequest($request);
```

Under the hood it uses [zend-inputfilter](https://docs.zendframework.com/zend-inputfilter/)
which makes all its [validators](https://docs.zendframework.com/zend-validator/set/) and
[filters](https://docs.zendframework.com/zend-filter/standard-filters/) available to you.

If you use a framework that doesn't handle PSR-7 requests, you can still reduce boilerplate
code by passing the request method yourself:

```php
$validationResult = $form->validate($submittedData, $_SERVER['REQUEST_METHOD']);
```

You can also leave the method validation out. It won't check for a valid `POST` method.

```php
$validationResult = $form->validate($submittedData);
```

### 3. Process validation result

Submitted data should be valid if it was a post and there are no validation messages set.

```php
// It should be valid if it was a post and if there are no validation messages
if ($validationResult->isValid()) {
    // Get filter submitted values
    $data = $validationResult->getValues();

    // Process data

    return new RedirectResponse('/');
}
```

If PSR-7 request methods are not available, you might can check for a valid
post method yourself.

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $validationResult->isValid()) {
    // ...
}
```

### 4. Render the form

Last step is rendering the form and injecting the submitted values and validation messages.

```php
// Render the form
return new HtmlResponse($this->template->render('app::edit', [
    'form' => $form->asString($validationResult),
]));
```

If you don't want the values and messages injected for you, just leave out the validation result.

```php
echo $form->asString();
```

Before rendering, the FormFactory removes any data validation attributes used to instantiate custom validation
(e.g. `data-validators`, `data-filters`). This also removes possible sensitive data that was used to setup
the validators.

The `$validationResult` is optional and triggers the following tasks:
- The FormFactory injects filtered submitted data into the input elements.
- The FormFactory adds error messages next to the input elements.
- The FormFactory sets the `aria-invalid="true"` attribute for invalid input elements.
- The FormFactory adds the bootstrap `has-danger` css class to the parent element.

## Submit button detection

Who doesn't want to know which button is clicked? For this to work the submit button must have a name attribute set.

```html
<form>
    <input type="submit" name="confirm" value="Confirm" />
    <button type="submit" name="cancel">Cancel</button>
</form>
```

You can check by the button name attribute if a specific button is clicked.

```php
// Returns a boolean
$validationResult->isClicked('confirm');
```

Or get the name of the clicked button.

```php
// Returns the name of the clicked button or null if no named was clicked
$validationResult->getClicked();
```

## Example

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
