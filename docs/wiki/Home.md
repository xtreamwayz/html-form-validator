As challenged by a [tweet](https://twitter.com/Ocramius/status/680817040429592576), this should extract validation
rules and filters from a html form and validate submitted user data against it.

It's pretty crazy what you have to do to get a form build. Create a lot of php classes for elements, validation,
etc. So why not build a html form and use the standard element attributes to extract the validation rules and filters.
Together with some powerful html compliant data attributes you can create forms, validation and filters in one place.

## Installation

```bash
$ composer require xtreamwayz/html-form-validator
```

## How does it work?

1. **Load the html form into the FormFactory**

    ```php
    $form = FormFactory::fromHtml($htmlForm, $defaultValues);
    ```

    - The FormFactory automatically creates default validators and filters for all input elements.
    - The FormFactory extracts additional custom validation rules and filters from the form.
    - The FormFactory optionally injects default data into the form input elements.

2. **Validate the form against submitted data**

    ```php
    $result = $form->validate($_POST);
    ```

    Under the hood it uses [zend-inputfilter](https://github.com/zendframework/zend-inputfilter) which makes all its
    [validators](http://framework.zend.com/manual/current/en/modules/zend.validator.set.html) and
    [filters](http://framework.zend.com/manual/current/en/modules/zend.filter.set.html) available to you.

3. **Render the form**

    ```php
    echo $form->asString($validationResult);
    ```

    Before rendering, the FormFactory removes any data validation attributes used to instantiate custom validation
    (e.g. ``data-validators``, ``data-filters``). This also removes possible sensitive data that was used to setup
    the validators.

    The ``$validationResult`` is optional and triggers the following tasks:
    - The FormFactory injects filtered submitted data into the input elements.
    - The FormFactory adds error messages next to the input elements.
    - The FormFactory sets the ``aria-invalid="true"`` attribute for invalid input elements.
    - The FormFactory adds the bootstrap ``has-danger`` css class to the parent element.
