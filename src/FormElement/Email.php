<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StringTrim as StringTrimFilter;
use Zend\Filter\StripNewlines as StripNewlinesFilter;
use Zend\Validator\EmailAddress as EmailAddressValidator;
use Zend\Validator\Explode as ExplodeValidator;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\StringLength as StringLengthValidator;
use const FILTER_VALIDATE_BOOLEAN;
use function filter_var;
use function sprintf;

class Email extends BaseFormElement
{
    protected function getFilters() : array
    {
        return [
            ['name' => StripNewlinesFilter::class],
            ['name' => StringTrimFilter::class],
        ];
    }

    protected function getValidators() : array
    {
        $validators = [];

        if ($this->node->hasAttribute('multiple')) {
            $validators[] = [
                'name'    => ExplodeValidator::class,
                'options' => [
                    'validator' => $this->getEmailValidator(),
                ],
            ];
        } else {
            $validators[] = $this->getEmailValidator();
        }

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

    protected function getEmailValidator() : array
    {
        return [
            'name'    => EmailAddressValidator::class,
            'options' => [
                'useMxCheck' => filter_var(
                    $this->node->getAttribute('data-validator-use-mx-check'),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ],
        ];
    }
}
