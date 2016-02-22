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
