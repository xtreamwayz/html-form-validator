<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\Input;
use Zend\Validator;

class Textarea extends AbstractFormElement
{
    public function attachValidators(Input $input, DOMElement $element)
    {
    }
}
