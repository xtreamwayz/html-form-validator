<?php

declare(strict_types = 1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\I18n\Validator\IsFloat as NumberValidator;
use Zend\Validator\GreaterThan as GreaterThanValidator;
use Zend\Validator\LessThan as LessThanValidator;
use Zend\Validator\Step as StepValidator;

class Range extends BaseFormElement
{
    protected function getValidators() : array
    {
        $validators = [];

        $validators[] = [
            'name' => NumberValidator::class,
        ];

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
