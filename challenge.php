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
        data-filters="alpha,stringtrim"
        data-validators="{'StringLength':[{'min':2,'max':140}],'Test':[]}"
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
        data-filters="striptags,stringtrim"
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
             ->attach(new Validator\StringLength([
                 'encoding' => 'UTF-8',
                 'min'      => 2,
                 'max'      => 140,
             ]));

        $subject = new Input('subject');
        $subject->getValidatorChain()
                ->attach(new Validator\StringLength([
                    'encoding' => 'UTF-8',
                    'min'      => 2,
                    'max'      => 140,
                ]));

        $this->add($name)
             ->add($subject);
    }
}

$_POST['name'] = '  Full Name  ';
$_POST['email'] = 'test@localhost';
$_POST['subject'] = 'Message subject  ';
$_POST['body'] = 'Body.';

$form = FormFactory::fromHtml($htmlForm, new ContactFilter());
$result = $form->validate($_POST); // Returns form validation result VO
//var_dump($result);

echo $form->asString($result);
