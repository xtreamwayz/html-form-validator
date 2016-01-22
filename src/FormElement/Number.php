<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\Input;
use Zend\Validator;

class Number extends AbstractFormElement
{
    public function attachValidators(Input $input, DOMElement $element)
    {
        $min = $element->getAttribute('min');
        $max = $element->getAttribute('max');

        if ($min && $max) {
            $input->getValidatorChain()
                  ->attach(new Validator\Between(['min' => $min, 'max' => $max]))
            ;
        } elseif ($min) {
            $input->getValidatorChain()
                  ->attach(new Validator\GreaterThan(['min' => $min]))
            ;
        } elseif ($max) {
            $input->getValidatorChain()
                  ->attach(new Validator\LessThan(['max' => $max]))
            ;
        }
    }
}
