<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\Validator\Date as DateValidator;

class DateTime extends BaseFormElement
{
    protected $format = 'Y-m-d\TH:i';

    protected function getValidators()
    {
        return [
            $this->getDateValidator(),
        ];
    }

    protected function getDateValidator()
    {
        return [
            'name'    => DateValidator::class,
            'options' => [
                'format' => $this->format,
            ],
        ];
    }
}
