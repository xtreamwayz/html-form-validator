<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\Identical as IdenticalValidator;

class Checkbox extends BaseFormElement
{
    protected function getValidators() : array
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
