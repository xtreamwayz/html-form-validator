This is a basic contact form with csrf protection.

```php
$htmlForm = <<<'HTML'
<form action="{{ path() }}" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-control-label" for="name">Name</label>
                <input type="text" id="name" name="name" required
                       placeholder="Your name" class="form-control"
                       data-reuse-submitted-value="true"
                       data-filters="striptags|stringtrim" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-control-label" for="email">Email address</label>
                <input type="email" id="email" name="email" required
                       placeholder="Your email address" class="form-control"
                       data-reuse-submitted-value="true"
                       data-filters="striptags|stringtrim" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-control-label" for="subject">Subject</label>
        <input type="text" id="subject" name="subject" required
               placeholder="Subject" class="form-control"
               data-reuse-submitted-value="true"
               data-filters="striptags|stringtrim" />
    </div>

    <div class="form-group">
        <label class="form-control-label" for="body">Message</label>
        <textarea id="body" name="body" rows="5" required
                  class="form-control" placeholder="Message"
                  data-reuse-submitted-value="true"
                  data-filters="stringtrim"></textarea>
    </div>

    <input type="hidden" name="token" value="{{ csrf-token }}" required
           data-validators="identical{token:{{ csrf-token }}}" />

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
HTML;

// Create form validator from a twig rendered form template
$form = FormFactory::fromHtml($template->render($htmlForm, [
    'csrf-token' => '123456'
]));

$_POST['name'] = 'Ocramius';
$_POST['email'] = 'no-reply@example.com';
$_POST['subject'] = 'WOAH!';
$_POST['body'] = 'Turns out that the idea became an actual thing: https://github.com/xtreamwayz/html-form-validator -
https://twitter.com/Ocramius/status/680817040429592576 #php #forms';

// Validate form and return form validation result object
$result = $form->validate($_POST);

// Inject error messages and filtered values from the result object
echo $form->asString($result);
```
