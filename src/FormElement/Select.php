<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\InArray as InArrayValidator;

class Select extends BaseFormElement
{
    protected function getValidators()
    {
        $validators = [];
        $haystack = [];

        /** @var \DOMElement $option */
        foreach ($this->node->getElementsByTagName('option') as $option) {
            $haystack[] = $option->getAttribute('value');
        }

        $validators[] = [
            'name'    => InArrayValidator::class,
            'options' => [
                'haystack' => $haystack,
            ],
        ];

        return $validators;
    }
}
