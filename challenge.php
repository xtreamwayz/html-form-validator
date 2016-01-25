<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use Xtreamwayz\HTMLFormValidator\FormFactory;

$htmlForm = <<<'HTML'
<form action="%s" method="post">
    <label for="name">Name:</label>
    <input
        type="text"
        id="name"
        name="name"
        value=""
        data-reuse-submitted-value="true"
        data-filters="stringtrim,alpha"
        required="required"
    />
    <input
        type="email"
        id="email"
        name="email"
        value=""
        aria-describedby="email-description"
        data-reuse-submitted-value="true"
        required="required"
    />
    <span id="email-description" class="help">Enter a valid email address</span>
    <input
        type="text"
        id="subject"
        name="subject"
        value=""
        data-reuse-submitted-value="true"
        data-filters="stringtrim"
        required="required"
    />
    <textarea
        type="text"
        name="body"
        data-reuse-submitted-value="true"
        required
    ></textarea>
    <input type="submit"/>
</form>
HTML;

use Zend\Filter;
use Zend\InputFilter\BaseInputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class ContactFilter extends BaseInputFilter
{
    public function __construct()
    {
        $name = new Input('name');
        $name->getValidatorChain()
             ->attach(new Validator\NotEmpty())
             ->attach(new Validator\StringLength([
                 'encoding' => 'UTF-8',
                 'min'      => 2,
                 'max'      => 140,
             ]));
        $name->getFilterChain()
             ->attach(new Filter\StringTrim())
             ->attach(new Filter\StripTags());

        $email = new Input('email');
        $email->getValidatorChain()
              ->attach(new Validator\NotEmpty())
              ->attach(new Validator\EmailAddress([
                  //'allow' => Validator\Hostname::ALLOW_DNS,
                  'domain'     => true,
                  'useMxCheck' => true,
              ]));

        $subject = new Input('subject');
        $subject->getValidatorChain()
                ->attach(new Validator\NotEmpty())
                ->attach(new Validator\StringLength([
                    'encoding' => 'UTF-8',
                    'min'      => 2,
                    'max'      => 140,
                ]));
        $subject->getFilterChain()
                ->attach(new Filter\StringTrim())
                ->attach(new Filter\StripTags());

        $body = new Input('body');
        $body->getValidatorChain()
             ->attach(new Validator\NotEmpty());

        $this->add($name)
             ->add($email)
             ->add($subject)
             ->add($body);
    }
}

$_POST['name'] = 'Full Name';
$_POST['email'] = 'test@localhost';
$_POST['subject'] = '   Message subject    ';
$_POST['body'] = 'Message body.';

$form = FormFactory::fromHtml($htmlForm, new ContactFilter());
$result = $form->validate($_POST); // returns form validation result VO
var_dump($result);

echo $form->asString($result);
