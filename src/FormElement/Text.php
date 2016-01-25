<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Text extends AbstractFormElement
{
    public function attachValidators(InputInterface $input, DOMElement $element)
    {
    }
}
