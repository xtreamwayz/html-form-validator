<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use InvalidArgumentException;

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
        $step = ($this->element->hasAttribute('step')) ? $this->element->getAttribute('step') : 1;

        if (is_numeric($step) && (int) $step == $step) {
            $this->attachValidatorByName('isint');
        } elseif (is_numeric($step) && (float) $step == $step) {
            $this->attachValidatorByName('isfloat', [
                'locale' => 'en'
            ]);
        } elseif ($step != 'any') {
            throw new InvalidArgumentException('Number step must be an int, float or the text "any"');
        }

        if ($this->element->hasAttribute('min')) {
            $this->attachValidatorByName('greaterthan', [
                'min'       => $this->element->getAttribute('min'),
                'inclusive' => true,
            ]);
        }

        if ($this->element->hasAttribute('max')) {
            $this->attachValidatorByName('lessthan', [
                'max'       => $this->element->getAttribute('max'),
                'inclusive' => true,
            ]);
        }

        if (is_numeric($step)) {
            $this->attachValidatorByName('step', [
                'step'      => $step,
                'baseValue' => ($this->element->hasAttribute('min')) ? $this->element->getAttribute('min') : 0,
            ]);
        }
    }
}
