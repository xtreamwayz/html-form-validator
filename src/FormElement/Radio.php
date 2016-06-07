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

use Zend\Validator\InArray as InArrayValidator;

class Radio extends BaseFormElement
{
    protected function getValidators() : array
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
