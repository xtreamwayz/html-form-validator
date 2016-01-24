<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use Xtreamwayz\HTMLFormValidator\FormFactory;

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
        required
    />
    <input type="text" name="country_code" pattern="[A-Za-z]{3}" />
    <input type="submit"/>
</form>
HTML;

//$form = FormFactory::fromHtml($htmlForm);
$config = new \Zend\ServiceManager\Config([]);
$inputFilterPluginManager = new \Zend\InputFilter\InputFilterPluginManager($config);
$inputFilterFactory = new \Zend\InputFilter\Factory($inputFilterPluginManager);
$form = new FormFactory($htmlForm, $inputFilterFactory);

$_POST['email'] = 'test@localhost';
$_POST['intNumber'] = 22;
$_POST['username'] = ' xtreamwayz 22 ';
$_POST['country_code'] = 'nl';
var_dump($form->validate($_POST)); // returns form validation result VO

echo $form->asString();
