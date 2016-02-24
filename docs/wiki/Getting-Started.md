There are only 4 steps needed to get you started.

```php
// Build the form
$form = FormFactory::fromHtml($htmlForm);

// Validate the form
$result = $form->validate($_POST);

// Process validation result
if ($result->isValid()) {
    $data = $result->getValues();
}

// Render the form
echo $form->asString();
```

The HTML5 standard validation rules are added for you [[input elements|API Form Elements]] so you don't need to 
repeat those over and over again. And then there are the special attributes with trigger standard validation: 
- [[max|API Attributes#max]]
- [[min|API Attributes#min]]
- [[step|API Attributes#step]]
- [[maxlength|API Attributes#maxlength]]
- [[minlength|API Attributes#minlength]]
- [[multiple|API Attributes#multiple]]
- [[pattern|API Attributes#pattern]]
- [[required|API Attributes#required]], [[aria-required|API Attributes#aria-required]]

And if you need more validation or specific filters there is a [[data-filters|API Attributes#data-filters]] and 
[[data-validators|API Attributes#data-validators]] attribute.

A full blown text input might look like:

```html
<input type="text" id="username" name="username" required
       placeholder="Your username" class="form-control"
       pattern="[a-z]{2,}" minlength="2" maxlength="16"
       data-reuse-submitted-value="true"
       data-filters="striptags|stringtrim"
       data-validators="" />
```

Let's go through all the steps needed to get this working inside a controller action method.

## The four steps

### 1. Build the form

Create the form from html. Nothing fancy here. In this case a template renderer is used and the default values are 
injected.

```php
// Build the form
$form = FormFactory::fromHtml($this->template->render('app::form', [
    'token' => $session->get('csrf'),
]));
```

### 2. Validate the form

The easiest way is if you use a framework that uses [PSR-7 requests](http://www.php-fig.org/psr/psr-7/).

```php
// Validate PSR-7 request and return a ValidationResponseInterface 
// It should only start validation if it was a post and if there are submitted values
$validationResult = $form->validateRequest($request);
```

If you use a framework that doesn't handle PSR-7 requests, you can still reduce boilerplate code by passing the 
request method yourself:

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

If you didn't use the PSR-7 request method, you might want to check for a valid post method yourself.
 
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

## Submit button detection

Who doesn't want to know which button is clicked? For this to the submit button must have a name attribute.

```html
<form>
    <input type="submit" name="confirm" value="Confirm" />
    <button type="submit" name="cancel">Cancel</button>
</form>
```

You can check by the name attribute if a specific button is clicked.

```php
// Returns a boolean
$validationResult->isClicked('confirm');
```

Without specifying a name, the name attribute value of the clicked button is returned.

```php
// Returns the name of the clicked button or null if no named was clicked
$validationResult->isClicked();
```

## Custom Validators and Filters

Setting up custom validators and filters is a bit more work but it isn't complicated. Instead of creating the 
FormFactory with its static `fromHtml` method, the constructor is needed with a configured Zend\InputFilter\Factory 
and a Interop\Container\ContainerInterface. 

```php
$config = [
    'zend-inputfilter' => [
        'validators' => [
            // Attach custom validators or override standard validators
            'invokables' => [
                'recaptcha' => Xtreamwayz\HTMLFormValidator\Validator\RecaptchaValidator::class,
            ],
        ],
        'filters'    => [
            // Attach custom filters or override standard filters
            'invokables' => [
            ],
        ],
    ],
];

// Create a container-interop compatible container with the custom validator configuration
$container = new Zend\ServiceManager\ServiceManager($dependencies);
$container->setService('config', $config);

// Use the InputFilterFactory to do all the work for you
$factory = new Xtreamwayz\HTMLFormValidator\InputFilterFactory();
$inputFilterFactory = $factory($container);

// Load the html form into the FormFactory
$form = new FormFactory($htmlForm, [], $inputFilterFactory);

// Validate the form
$result = $form->validate($_POST);

// Display the plain form
echo $form->asString();

// Display the form with the submitted values and validation messages 
echo $form->asString($validationResult);
```
