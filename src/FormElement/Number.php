<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Laminas\Validator\GreaterThan as GreaterThanValidator;
use Laminas\Validator\LessThan as LessThanValidator;
use Laminas\Validator\Regex as RegexValidator;
use Laminas\Validator\Step as StepValidator;

class Number extends BaseFormElement
{
    protected function getValidators() : array
    {
        $validators = [];

        // HTML5 always transmits values in the format "1000.01", without a
        // thousand separator. The prior use of the i18n Float validator
        // allowed the thousand separator, which resulted in wrong numbers
        // when casting to float.
        $validators[] = new RegexValidator('(^-?\d*(\.\d+)?$)');

        if ($this->node->hasAttribute('min')) {
            $validators[] = $this->getMinValidator();
        }

        if ($this->node->hasAttribute('max')) {
            $validators[] = $this->getMaxValidator();
        }

        if (! $this->node->hasAttribute('step')
            || $this->node->getAttribute('step') !== 'any'
        ) {
            $validators[] = $this->getStepValidator();
        }

        return $validators;
    }

    protected function getMinValidator() : array
    {
        return [
            'name'    => GreaterThanValidator::class,
            'options' => [
                'min'       => $this->node->getAttribute('min'),
                'inclusive' => true,
            ],
        ];
    }

    protected function getMaxValidator() : array
    {
        return [
            'name'    => LessThanValidator::class,
            'options' => [
                'max'       => $this->node->getAttribute('max'),
                'inclusive' => true,
            ],
        ];
    }

    protected function getStepValidator() : array
    {
        return [
            'name'    => StepValidator::class,
            'options' => [
                'baseValue' => $this->node->getAttribute('min') ?: 0,
                'step'      => $this->node->getAttribute('step') ?: 1,
            ],
        ];
    }
}
