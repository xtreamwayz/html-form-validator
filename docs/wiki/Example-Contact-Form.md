This is a basic contact form.

```php
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
$form = FormFactory::fromHtml($template->render($htmlForm, [
    'csrf-token' => '123456'
]));

$_POST['name'] = 'John Doe';
$_POST['email'] = 'john.doe@example.com';
$_POST['subject'] = 'Subject of message';
$_POST['body'] = 'ow are you doing.';

// Validate form and return form validation result object
$result = $form->validate($_POST);

// Inject error messages and filtered values from the result object
echo $form->asString($result);
```
