<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StripNewlines as StripNewlinesFilter;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\StringLength as StringLengthValidator;

class Password extends BaseFormElement
{
    protected function getFilters()
    {
        return [
            ['name' => StripNewlinesFilter::class],
        ];
    }

    protected function getValidators()
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
