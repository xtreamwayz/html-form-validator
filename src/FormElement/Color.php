<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Filter\StringToLower as StringToLowerFilter;
use Zend\Validator\Regex as RegexValidator;

class Color extends BaseFormElement
{
    protected function getFilters()
    {
        return [
            ['name' => StringToLowerFilter::class],
        ];
    }

    protected function getValidators()
    {
        return [
            [
                'name'    => RegexValidator::class,
                'options' => [
                    'pattern' => '/^#[0-9a-fA-F]{6}$/',
                ],
            ],
        ];
    }
}
