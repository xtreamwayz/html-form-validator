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
        data-filters="striptags|stringtrim"
        data-validators="stringlength{min:2,max:140}"
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
        data-filters="striptags|stringtrim"
        required="required"
    />
    <textarea
        name="body"
        data-reuse-submitted-value="true"
        required
    ></textarea>
    <input type="date" name="date" data-reuse-submitted-value="true" />
    <input type="month" name="month" data-reuse-submitted-value="true" />
    <input type="week" name="week" data-reuse-submitted-value="true" />
    <input type="time" name="time" data-reuse-submitted-value="true" />
    <input type="datetime-local" name="datetime-local" data-reuse-submitted-value="true" />
    <input type="color" name="color" data-reuse-submitted-value="true" />
    <input type="range" name="range" min="0" max="10" data-reuse-submitted-value="true" />
    <input type="submit" />
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
$_POST['date'] = date('Y-m-d');
$_POST['month'] = date('Y-m');
$_POST['week'] = date('Y-\WW');
$_POST['time'] = date('H:i');
$_POST['datetime-local'] = date('Y-m-d\TH:i');
$_POST['color'] = '#fefefe';
$_POST['range'] = '10';

//$form = FormFactory::fromHtml($htmlForm, new ContactFilter());
$form = FormFactory::fromHtml($htmlForm, ['body' => 'default value']);
echo $form->asString();

$result = $form->validate($_POST); // Returns form validation result VO
var_dump($result);
echo $form->asString($result);
