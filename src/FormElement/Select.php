<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Laminas\Validator\Explode as ExplodeValidator;
use Laminas\Validator\InArray as InArrayValidator;

class Select extends BaseFormElement
{
    protected function getValidators(): array
    {
        $validators = [];

        if ($this->node->hasAttribute('multiple')) {
            $validators[] = [
                'name'    => ExplodeValidator::class,
                'options' => [
                    'validator'      => $this->getInArrayValidator(),
                    'valueDelimiter' => null, // skip explode if only one value
                ],
            ];
        } else {
            $validators[] = $this->getInArrayValidator();
        }

        return $validators;
    }

    private function getInArrayValidator(): array
    {
        return [
            'name'    => InArrayValidator::class,
            'options' => [
                'haystack' => $this->getValueOptions(),
                'strict'   => false,
            ],
        ];
    }

    private function getValueOptions(): array
    {
        $haystack = [];

        /** @var DOMElement $option */
        foreach ($this->node->getElementsByTagName('option') as $option) {
            $haystack[] = $option->getAttribute('value');
        }

        return $haystack;
    }
}
