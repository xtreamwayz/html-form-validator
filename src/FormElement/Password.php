<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

declare(strict_types = 1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StripNewlines as StripNewlinesFilter;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\StringLength as StringLengthValidator;

class Password extends BaseFormElement
{
    protected function getFilters() : array
    {
        return [
            ['name' => StripNewlinesFilter::class],
        ];
    }

    protected function getValidators() : array
    {
        $validators = [];

        if ($this->node->hasAttribute('minlength') || $this->node->hasAttribute('maxlength')) {
            $validators[] = [
                'name'    => StringLengthValidator::class,
                'options' => [
                    'min' => $this->node->getAttribute('minlength') ?: 0,
                    'max' => $this->node->getAttribute('maxlength') ?: null,
                ],
            ];
        }

        if ($this->node->hasAttribute('pattern')) {
            $validators[] = [
                'name'    => RegexValidator::class,
                'options' => [
                    'pattern' => sprintf('/%s/', $this->node->getAttribute('pattern')),
                ],
            ];
        }

        return $validators;
    }
}
