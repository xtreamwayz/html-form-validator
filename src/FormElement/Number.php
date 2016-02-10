<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\InputFilter\InputInterface;

class Number extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators(InputInterface $input, DOMElement $element)
    {
        $baseValue = 0;
        $min = $element->getAttribute('min');
        $max = $element->getAttribute('max');
        $step = $element->getAttribute('step');

        if ($min && $max) {
            $baseValue = $min;
            $this->attachValidatorByName($input, 'between', [
                'min'       => $min,
                'max'       => $max,
                'inclusive' => true,
            ]);
        } elseif ($min) {
            $baseValue = $min;
            $this->attachValidatorByName($input, 'greaterthan', [
                'min'       => $min,
                'inclusive' => true,
            ]);
        } elseif ($max) {
            $this->attachValidatorByName($input, 'lessthan', [
                'max'       => $max,
                'inclusive' => true,
            ]);
        }

        if ($step) {
            $this->attachValidatorByName($input, 'step', [
                'step'      => $step,
                'baseValue' => $baseValue,
            ]);
        }
    }
}
