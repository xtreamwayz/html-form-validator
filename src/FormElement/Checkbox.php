<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

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
