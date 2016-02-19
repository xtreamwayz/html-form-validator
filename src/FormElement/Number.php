<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use InvalidArgumentException;
use Zend\I18n\Validator\IsFloat;
use Zend\I18n\Validator\IsInt;
use Zend\Validator\GreaterThan;
use Zend\Validator\LessThan;
use Zend\Validator\Step;

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
            $this->attachValidatorByName(IsInt::class);
        } elseif (is_numeric($step) && (float) $step == $step) {
            $this->attachValidatorByName(IsFloat::class, [
                'locale' => 'en',
            ]);
        } elseif ($step != 'any') {
            throw new InvalidArgumentException('Number step must be an int, float or the text "any"');
        }

        if ($this->element->hasAttribute('min')) {
            $this->attachValidatorByName(GreaterThan::class, [
                'min'       => $this->element->getAttribute('min'),
                'inclusive' => true,
            ]);
        }

        if ($this->element->hasAttribute('max')) {
            $this->attachValidatorByName(LessThan::class, [
                'max'       => $this->element->getAttribute('max'),
                'inclusive' => true,
            ]);
        }

        if (is_numeric($step)) {
            $this->attachValidatorByName(Step::class, [
                'step'      => $step,
                'baseValue' => ($this->element->hasAttribute('min')) ? $this->element->getAttribute('min') : 0,
            ]);
        }
    }
}
