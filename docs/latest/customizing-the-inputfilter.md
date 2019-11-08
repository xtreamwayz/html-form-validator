---
title: Customizing the InputFilter
type: project
layout: page
project: html-form-validator
version: v1
---

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

## Custom Validators and Filters

Setting up custom validators and filters is a bit more work but it isn't complicated. Instead of creating the
FormFactory with its static `fromHtml` method, the constructor is needed with a configured `Zend\InputFilter\Factory`
and a `Psr\Container\ContainerInterface`.

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
$formFactory = new FormFactory($inputFilterFactory);

// Create a form instance
$form = $formFactory->fromHtml($html);

// Validate the form
$result = $form->validate($_POST);

// Display the plain form
echo $form->asString();

// Display the form with the submitted values and validation messages
echo $form->asString($validationResult);
```
