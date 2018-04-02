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
