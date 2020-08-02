<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Xtreamwayz\HTMLFormValidator\FormElement\Number as NumberElement;
use Laminas\I18n\Validator\IsFloat as NumberValidator;

class Range extends NumberElement
{
    protected function getValidators() : array
    {
        $validators = [];

        $validators[] = [
            'name' => NumberValidator::class,
        ];

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
}
