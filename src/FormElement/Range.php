<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Xtreamwayz\HTMLFormValidator\FormElement\Number as NumberElement;
use Zend\I18n\Validator\IsFloat as NumberValidator;

class Range extends NumberElement
{
    protected function getValidators()
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

        if (!$this->node->hasAttribute('step')
            || 'any' !== $this->node->getAttribute('step')
        ) {
            $validators[] = $this->getStepValidator();
        }

        return $validators;
    }
}
