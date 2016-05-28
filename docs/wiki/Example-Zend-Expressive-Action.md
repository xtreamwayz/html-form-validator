Let's take the contact form as an example and use it in a zend-expressive application with Twig as a renderer. The 
form is pretty basic and has extra StringTrim and StripTags filters for the name and subject input fields. It also 
has csrf protection with a hidden token which is validated with the identical validator.

```html
<!-- // templates/app/contact-form.html.twig -->
<form action="{{ path() }}" method="post">
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
<?php // src/App/Action/ContactAction.php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PSR7Session\Http\SessionMiddleware;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class ContactAction
{
    private $template;

    public function __construct(
        TemplateRendererInterface $template
    ) {
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        // Use [PSR7Session](https://github.com/Ocramius/PSR7Session) to store the session data.
    
        /* @var \PSR7Session\Session\SessionInterface $session */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        // Generate csrf token if needed
        if (!$session->get('csrf')) {
            $session->set('csrf', md5(random_bytes(32)));
        }

        // Generate the form from the template with the template renderer and inject the csrf token
        $form = FormFactory::fromHtml($this->template->render('app::contact-form', [
            'token' => $session->get('csrf'),
        ]));

        // Validate PSR-7 request and get a validation result
        $validationResult = $form->validateRequest($request);
        
        // It should be valid if it was a post and if there are no validation messages
        if ($validationResult->isValid()) {
            // Get filtered submitted values
            $data = $validationResult->getValues();
        
            // Process data
        
            return new RedirectResponse('/');
        }
        
        // Display the form and inject the validation messages if there are any
        return new HtmlResponse($this->template->render('app::edit', [
            'form' => $form->asString($validationResult),
        ]));
    }
}
```
