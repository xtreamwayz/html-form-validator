# html-form-validator

*This is a proof of concept!*

As challenged by a [tweet](https://twitter.com/Ocramius/status/680817040429592576), this should validate a html form.

It's pretty crazy what you have to do to get a form build. Create a lot of php classes for elements, validation,
etc. So why not build a html5 form and use the element attributes to generate the validator and filters.

```php
$htmlForm = <<<'HTML'
<form action="%s" method="post">
    <label for="email">Email:</label>
    <input
        type="email"
        id="email"
        name="email"
        value=""
        aria-describedby="email-description"
        data-reuse-submitted-value="true"
        data-validator="email-address"
        required="required"
    />
    <span id="email-description" class="help">Enter a valid email address</span>
    <input
        type="number"
        id="intNumber"
        name="intNumber"
        value=""
        min="1"
        max="20"
        data-reuse-submitted-value="true"
        data-validator="between"
    />
    <input
        type="text"
        name="username"
        value=""
        data-reuse-submitted-value="true"
        data-filters="stringtrim,alpha"
    />
    <input type="submit"/>
</form>
HTML;

$form = FormFactory::fromHtml($htmlForm);

$_POST['email'] = 'test@localhost';
$_POST['intNumber'] = 22;
$_POST['username'] = ' xtreamwayz 22 ';
var_dump($form->validate($_POST)); // returns form validation result VO

echo $form->asString();
```

## Resources
- http://www.w3schools.com/html/html_form_elements.asp
- http://www.w3schools.com/tags/tag_input.asp
