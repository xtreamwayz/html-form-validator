<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StripNewlines as StripNewlinesFilter;
use Zend\I18n\Validator\PhoneNumber as PhoneNumberValidator;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\StringLength as StringLengthValidator;
use function sprintf;

class Tel extends BaseFormElement
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

        if ($this->node->hasAttribute('data-validator-country')) {
            // Only use the validator if a country is set
            $validators[] = [
                'name'    => PhoneNumberValidator::class,
                'options' => [
                    'country' => $this->node->getAttribute('data-validator-country') ?: null,
                ],
            ];
        } elseif (! $this->node->hasAttribute('pattern')) {
            // Use a very loose pattern for validation
            $this->node->setAttribute('pattern', '^\+[0-9]{1,3}[0-9\s]{4,17}$');
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
}
