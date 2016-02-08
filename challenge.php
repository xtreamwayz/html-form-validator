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
        required
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
        aria-required="true"
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

    <input type="password" name="password" required />
    <input type="password" name="password-confirm" required data-validators="identical{token:password}" />

    <input type="checkbox" name="checkbox" value="value" />

    <input type="radio" name="radio" value="value1" data-reuse-submitted-value="true" />
    <input type="radio" name="radio" value="value2" data-reuse-submitted-value="true" />
    <input type="radio" name="radio" value="value3" data-reuse-submitted-value="true" />

    <input type="file" name="file" />

    <select name="select" required data-reuse-submitted-value="true">
        <option value="option1">Option 1</option>
        <option value="option2">Option 2</option>
        <option value="option3">Option 3</option>
    </select>

    <select name="cars" data-reuse-submitted-value="true">
        <optgroup label="Swedish Cars">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
        </optgroup>
        <optgroup label="German Cars">
            <option value="volkswagen">Volkswagen</option>
            <option value="audi">Audi</option>
        </optgroup>
    </select>

    <input list="browsers" name="browser" required />
    <datalist id="browsers">
        <option value="Internet Explorer" />
        <option value="Firefox" />
        <option value="Chrome" />
        <option value="Opera" />
        <option value="Safari" />
    </datalist>

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
$_POST['password'] = '123456';
$_POST['password-confirm'] = '123456';
$_POST['checkbox'] = 'value';
$_POST['radio'] = 'value1';
$_POST['select'] = 'option2';
$_POST['cars'] = 'volkswagen';
$_POST['browser'] = 'FireFoxxxxx';

//$form = FormFactory::fromHtml($htmlForm, new ContactFilter());
$form = FormFactory::fromHtml($htmlForm, [
    'body' => 'default value',
    'radio' => 'value1',
    'select' => 'option1',
    'cars' => 'audi',
    'browser' => 'Chrome',
]);
//echo $form->asString();

$result = $form->validate($_POST); // Returns form validation result VO
//var_dump($result);
echo $form->asString($result);
