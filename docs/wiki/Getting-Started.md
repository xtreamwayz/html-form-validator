There are only 3 methods that are needed to get you started.

```php
// Load the html form into the FormFactory
$form = FormFactory::fromHtml($htmlForm);

// Validate the form
$result = $form->validate($_POST);

// Display the form
echo $form->asString();

// Display the form with the submitted values and validation messages 
echo $form->asString($validationResult);
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

Using this in a controller action method could look like this:

```php
// Build the form validation
$form = FormFactory::fromHtml($this->template->render('app::form', [
    'token'  => $session->get('csrf'),
]));

// Check if the request was a post
if ($request->getMethod() === 'POST') {
    // Validate the result
    $validationResult = $form->validate((array) $request->getParsedBody());

    // If the submitted data is valid...
    if ($validationResult->isValid()) {
        // Get filtered submitted values
        $data = $validationResult->getValues();

        // Process data

        // Redirect to
        return new RedirectResponse('/');
    }
}

// Display the form and inject the validation messages if there are any
return new HtmlResponse($this->template->render('app::edit', [
    'form' => isset($validationResult) ? $form->asString($validationResult) : $form->asString(),
]));
```

## Handling PSR-7 Post Requests

But wait, there is still a lot of boilerplate code. This can be done with less code if there is a
[PSR-7 requests](http://www.php-fig.org/psr/psr-7/).

In stead of using `$form->validate($submittedData)` there is a method to handle PSR-7 requests and do some magic for 
you: `$validationResult = $form->validateRequest($request);`.

```php
// Build the form validation
$form = FormFactory::fromHtml($this->template->render('app::form', [
    'token'  => $session->get('csrf'),
]));

// Validate PSR-7 request and return a ValidationResponseInterface 
// It should only start validation if it was a post and if there are submitted values
$validationResult = $form->validateRequest($request);

// It should be valid if it was a post and if there are no validation messages
if ($validationResult->isValid()) {
    // Get filter submitted values
    $data = $validationResult->getValues();

    // Process data

    return new RedirectResponse('/');
}

// Display the form and inject the validation messages if there are any
return new HtmlResponse($this->template->render('app::edit', [
    'form' => $form->asString($validationResult),
]));
```

If you use a framework that doesn't handle PSR-7 requests, you can still reduce boilerplate code by passing the 
request method yourself:

```php
$validationResult = $form->validate($submittedData, $_SERVER['REQUEST_METHOD']);
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
