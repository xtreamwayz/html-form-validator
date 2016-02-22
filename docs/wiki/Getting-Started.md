There are only 3 methods that are needed to get you started.

```php
// Load the html form into the FormFactory
$form = FormFactory::fromHtml($htmlForm);

// Validate the form
$result = $form->validate($_POST);

// Display the plain form
echo $form->asString();

// Display the form with the submitted values and validation messages 
echo $form->asString($validationResult);
```


## Custom Validators and Filters

Setting up custom validators and filters is a bit more work but it isn't complicated. Instead of creating the 
FormFactory with its static `fromHtml` method, the constructor is needed with a configured Zend\InputFilter\Factory 
and a Interop\Container\ContainerInterface. 

```php
$config = [
    'dependencies' => [
        'invokables' => [
        ],
        'factories'  => [
            Zend\InputFilter\Factory::class => Xtreamwayz\HTMLFormValidator\InputFilterFactory::class,
        ],
    ],

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

// Create the container with the custom validator configuration
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
