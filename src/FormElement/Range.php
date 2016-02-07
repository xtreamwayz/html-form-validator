<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

class Range extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $min = $element->getAttribute('min');
        $max = $element->getAttribute('max');
        $step = $element->getAttribute('step');

        if ($min && $max) {
            $input->getValidatorChain()
                  ->attach(new Validator\Between([
                      'min' => $min,
                      'max' => $max,
                      'inclusive' => true
                  ]));
        } elseif ($min) {
            $input->getValidatorChain()
                  ->attach(new Validator\GreaterThan([
                      'min' => $min,
                      'inclusive' => true
                  ]));
        } elseif ($max) {
            $input->getValidatorChain()
                  ->attach(new Validator\LessThan([
                      'max' => $max,
                      'inclusive' => true
                  ]));
        }

        if ($step) {
            $input->getValidatorChain()
                  ->attach(new Validator\Step(['step' => $step]));
        }
    }
}
