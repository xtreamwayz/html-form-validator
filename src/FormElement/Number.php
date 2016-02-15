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

        var_dump('step:' . $step .':'. (int) $step);

        if (is_numeric($step) && (int) $step == $step) {
            var_dump(__LINE__);
            $this->attachValidatorByName('isint');
        } elseif (is_numeric($step) && (float) $step == $step) {
            var_dump(__LINE__);
            $this->attachValidatorByName('isfloat', [
                'locale' => 'en'
            ]);
        } elseif ($step != 'any') {
            var_dump(__LINE__);
            throw new InvalidArgumentException('Number step must be an int, float or the text "any"');
        }

        if ($this->element->hasAttribute('min')) {
            var_dump('min:' . $this->element->getAttribute('min'));
            $this->attachValidatorByName('greaterthan', [
                'min'       => $this->element->getAttribute('min'),
                'inclusive' => true,
            ]);
        }

        if ($this->element->hasAttribute('max')) {
            var_dump('max:' . $this->element->getAttribute('max'));
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
