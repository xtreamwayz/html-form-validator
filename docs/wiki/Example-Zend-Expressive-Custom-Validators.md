Let's take the contact form as an example and use it in a zend-expressive application with Twig as a renderer. The 
form is pretty basic and has extra StringTrim and StripTags filters for the name and subject input fields. It also 
has csrf protection with a hidden token which is validated with the identical validator. And use a custom recaptcha 
validator for bot protection.

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

    <div class="form-group">
        <div class="form-group g-recaptcha" aria-required="true"
             data-input-name="g-recaptcha-response" data-validators="recaptcha{key:{{ recaptcha_priv_key }}}"
             data-sitekey="{{ recaptcha_pub_key }}" data-theme="light"></div>
    </div>

    <input type="hidden" name="token" value="{{ token }}" data-validators="identical{token:{{ token }}" required />

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

This is an example of how an Action may look like. The InputFilterFactory is injected which gives the FormFactory 
access to the custom recaptcha validator.

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
use Zend\InputFilter\Factory as InputFilterFactory;

class ContactAction
{
    private $template;

    private $inputFilterFactory;

    public function __construct(
        TemplateRendererInterface $template,
        InputFilterFactory $inputFilterFactory
    ) {
        $this->template = $template;
        $this->inputFilterFactory = $inputFilterFactory;
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

        // Generate csrf token
        if (!$session->get('csrf')) {
            $session->set('csrf', md5(random_bytes(32)));
        }

        // Build the form validation from the template with the template renderer and inject the csrf token.
        // The InputFilterFactory is added to have access to the custom recaptcha validator. 
        $form = FormFactory::fromHtml($this->template->render('app::form', [
            'token'  => $session->get('csrf'),
        ]), $this->inputFilterFactory);
        
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

To register the custom validator it needs to be added to the configuration.

```php
<?php // config/autoload/forms.global.php

return [
    'dependencies' => [
        'invokables' => [
        ],
        'factories'  => [
            // Use the InputFilterFactory helper to configure the InputFactory
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
```
