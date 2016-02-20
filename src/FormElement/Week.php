<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;
use Zend\Validator\Regex as RegexValidator;

class Week extends DateTimeElement
{
    protected function getDateValidator()
    {
        return [
            'name'    => RegexValidator::class,
            'options' => [
                'pattern' => '/^[0-9]{4}\-W[0-9]{2}$/',
            ],
        ];
    }
}
