<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\InArray as InArrayValidator;

class Radio extends BaseFormElement
{
    protected function getValidators()
    {
        $validators = [];
        $haystack = [];

        $xpath = new \DOMXPath($this->document);

        /** @var \DOMElement $option */
        foreach ($xpath->query('//input[@type="radio"][@name="' . $this->getName() . '"]') as $option) {
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
