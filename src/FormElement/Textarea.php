<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\StringLength as StringLengthValidator;

class Textarea extends BaseFormElement
{
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

        return $validators;
    }
}
