Symfony forms can be a real pain to design and even worse to debug. Luckily you are not bound to use Symfony forms and
can use something else if you like to. For example this html-form-factory :)

Let's take the contact form as an example and use it in a Symfony application. For the fun of it the ADR
(Action-Domain-Response) pattern is being to used which can easily be achieved by registering the action as a service.
Or use the [DunglasActionBundle](https://github.com/dunglas/DunglasActionBundle/) to do this for you.

The form is pretty basic and has extra StringTrim and StripTags filters for the name and subject input fields. It
also has csrf protection with a hidden token which is validated with the identical validator.

```html
<!-- // app/Resources/views/contact-form.html.twig -->
<form action="{{ app.request.schemeAndHttpHost ~ app.request.requestUri }}" method="post">
    <div class="form-group">
        <label class="form-control-label" for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Your name" required
               data-reuse-submitted-value="true" data-filters="stringtrim|striptags"
               class="form-control" />
    </div>

    <div class="form-group">
        <label class="form-control-label" for="email">Email address</label>
        <input type="email" id="email" name="email" placeholder="Your email address"
               data-reuse-submitted-value="true" required
               class="form-control" />
    </div>

    <div class="form-group">
        <label class="form-control-label" for="subject">Subject</label>
        <input type="text" id="subject" name="subject" placeholder="Subject" required
               data-reuse-submitted-value="true" data-filters="stringtrim|striptags"
               class="form-control" />
    </div>

    <div class="form-group">
        <label class="form-control-label" for="body">Message</label>
        <textarea id="body" name="body" rows="5" required
                  data-reuse-submitted-value="true"
                  class="form-control" placeholder="Message"></textarea>
    </div>

    <!-- csrf protection -->
    <input type="hidden" name="token" value="{{ token }}" data-validators="identical{token:{{ token }}" required />

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

This is an example of how an Action may look like. We'll walk through each step in the comments.

```php
<?php // src/AppBundle/Action/ContactAction.php

namespace AppBundle\Action;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Xtreamwayz\HTMLFormValidator\FormFactory;

class ContactAction
{
    private $router;
    private $template;

    public function __construct(
        RouterInterface $router,
        EngineInterface $template,
    ) {
        $this->router   = $router;
        $this->template = $template;
    }

    /**
     * @Route("/contact", name="contact")
     * @Method({"GET", "POST"})
     */
    public function __invoke(Request $request)
    {
        // Generate csrf token if needed
        $session = $request->getSession();
        if (!$session->get('csrf')) {
            $session->set('csrf', md5(random_bytes(32)));
        }

        // Generate the form from the template with the template renderer and inject the csrf token
        $form = FormFactory::fromHtml($this->template->render('contact-form.html.twig', [
            'token' => $session->get('csrf'),
        ]));

        // Validate request and get a validation result. The request method is passed to check if the form is
        // being posted.
        $validationResult = $form->validate($request->request->all(), $request->getMethod());

        // It should be valid if it was a post and if there are no validation messages
        if ($validationResult->isValid()) {
            // Get filtered submitted values
            $data = $validationResult->getValues();

            // Process data

            return new RedirectResponse($this->router->generate('homepage'));
        }

        // Display the form and inject the validation messages if there are any
        return new HtmlResponse($this->template->render('app::edit', [
            'form' => $form->asString($validationResult),
        ]));
    }
}
```
