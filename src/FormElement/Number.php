<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

class Number extends AbstractFormElement
{
    /**
     * @inheritdoc
     */
    protected function attachDefaultFilters()
    {
    }

    /**
     * @inheritdoc
     */
    protected function attachDefaultValidators()
    {
        $baseValue = 0;
        $min = $this->element->getAttribute('min');
        $max = $this->element->getAttribute('max');
        $step = $this->element->getAttribute('step');

        $this->attachValidatorByName('isint');

        if ($min && $max) {
            $baseValue = $min;
            $this->attachValidatorByName('between', [
                'min'       => $min,
                'max'       => $max,
                'inclusive' => true,
            ]);
        } elseif ($min) {
            $baseValue = $min;
            $this->attachValidatorByName('greaterthan', [
                'min'       => $min,
                'inclusive' => true,
            ]);
        } elseif ($max) {
            $this->attachValidatorByName('lessthan', [
                'max'       => $max,
                'inclusive' => true,
            ]);
        }

        if ($step) {
            $this->attachValidatorByName('step', [
                'step'      => $step,
                'baseValue' => $baseValue,
            ]);
        }
    }
}
