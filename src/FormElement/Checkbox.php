<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\Identical as IdenticalValidator;

class Checkbox extends BaseFormElement
{
    protected function getValidators()
    {
        return [
            [
                'name'    => IdenticalValidator::class,
                'options' => [
                    'token' => $this->node->getAttribute('value'),
                ],
            ],
        ];
    }
}
