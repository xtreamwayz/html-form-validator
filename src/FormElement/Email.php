<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StringTrim as StringTrimFilter;
use Zend\Filter\StripNewlines as StripNewlinesFilter;
use Zend\Validator\EmailAddress as EmailAddressValidator;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\StringLength as StringLengthValidator;

class Email extends BaseFormElement
{
    protected function getFilters()
    {
        return [
            ['name' => StripNewlinesFilter::class],
            ['name' => StringTrimFilter::class],
        ];
    }

    protected function getValidators()
    {
        $validators = [];

        $validators[] = [
            'name'    => EmailAddressValidator::class,
            'options' => [
                'useMxCheck' => filter_var(
                    $this->node->getAttribute('data-validator-use-mx-check'),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ],
        ];

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
