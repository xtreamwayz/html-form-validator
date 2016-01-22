<?php

namespace Xtreamwayz\HTMLFormValidator\InputType;

use DOMElement;
use Zend\InputFilter\Input;
use Zend\Validator;

class Textarea extends AbstractInputType
{
    public function attachValidators(Input $input, DOMElement $element)
    {
    }
}
