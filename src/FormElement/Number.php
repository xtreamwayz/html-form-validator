<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\GreaterThan as GreaterThanValidator;
use Zend\Validator\LessThan as LessThanValidator;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\Step as StepValidator;

class Number extends BaseFormElement
{
    protected function getValidators()
    {
        $validators = [];

        // HTML5 always transmits values in the format "1000.01", without a
        // thousand separator. The prior use of the i18n Float validator
        // allowed the thousand separator, which resulted in wrong numbers
        // when casting to float.
        $validators[] = new RegexValidator('(^-?\d*(\.\d+)?$)');

        if ($this->node->hasAttribute('min')) {
            $validators[] = [
                'name'    => GreaterThanValidator::class,
                'options' => [
                    'min'       => $this->node->getAttribute('min'),
                    'inclusive' => true,
                ],
            ];
        }

        if ($this->node->hasAttribute('max')) {
            $validators[] = [
                'name'    => LessThanValidator::class,
                'options' => [
                    'max'       => $this->node->getAttribute('max'),
                    'inclusive' => true,
                ],
            ];
        }

        if (!$this->node->hasAttribute('step')
            || 'any' !== $this->node->getAttribute('step')
        ) {
            $validators[] = [
                'name'    => StepValidator::class,
                'options' => [
                    'baseValue' => $this->node->getAttribute('min') ?: 0,
                    'step'      => $this->node->getAttribute('step') ?: 1,
                ],
            ];
        }

        return $validators;
    }
}
